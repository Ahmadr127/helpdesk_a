<?php

namespace App\Http\Controllers\AdministrasiUmum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPerbaikan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
            $timeFilter = $request->input('time_filter', 'all');
            $statusFilter = $request->input('status_filter', 'all');

        // Get base query
            $query = OrderPerbaikan::query();

            // Apply time filter
            switch ($timeFilter) {
                case 'today':
                $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                    break;
            }

            // Apply status filter if not 'all'
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

        // Get statistics
            $totalOrders = $query->count();
        $pendingOrders = (clone $query)->where('status', 'pending')->count();
        $rejectedOrders = (clone $query)->where('status', 'reject')->count();
        $confirmedOrders = (clone $query)->where('status', 'konfirmasi')->count();

        // Get order trends (group by date)
        $orderTrends = $this->getOrderTrends($timeFilter, $statusFilter);

        // Get status distribution
        $statusDistribution = [
            'pending' => $pendingOrders,
            'reject' => $rejectedOrders,
            'konfirmasi' => $confirmedOrders,
        ];

        // Get recent orders
        $recentOrders = (clone $query)
            ->with(['creator', 'location'])
            ->latest()
            ->take(8)
            ->get();

        $totalRecentOrders = $totalOrders;

        return view('administrasi-umum.dashboard.index', compact(
            'timeFilter',
            'statusFilter',
            'totalOrders',
            'pendingOrders',
            'rejectedOrders',
            'confirmedOrders',
            'orderTrends',
            'statusDistribution',
            'recentOrders',
            'totalRecentOrders'
        ));
    }

    private function getOrderTrends($timeFilter, $statusFilter)
    {
        // Base query
        $query = OrderPerbaikan::query();

        // Apply status filter if not 'all'
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Set time range and group format based on filter
        switch ($timeFilter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $groupFormat = 'HH24:00'; // Group by hour for PostgreSQL
                $formatType = 'hour';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $groupFormat = 'YYYY-MM-DD'; // Group by day for PostgreSQL
                $formatType = 'day';
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $groupFormat = 'YYYY-MM-DD'; // Group by day for PostgreSQL
                $formatType = 'day';
                break;
            default: // 'all' or any other value
                $startDate = Carbon::now()->subMonths(2); // Last 2 months
                $endDate = Carbon::now();
                $groupFormat = 'YYYY-MM-DD'; // Group by day for PostgreSQL
                $formatType = 'day';
                break;
        }

        // Apply date range to query
        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Group by date and count - using PostgreSQL TO_CHAR instead of MySQL DATE_FORMAT
        $results = $query->select(
            DB::raw("TO_CHAR(created_at, '{$groupFormat}') as date"),
            DB::raw('COUNT(*) as count')
        )
                ->groupBy('date')
        ->orderBy('date', 'asc')
                ->get();

        // If no results, return empty collection
        if ($results->isEmpty()) {
            return collect([]);
        }

        // Fill in missing dates if necessary (for week or month views)
        if ($timeFilter !== 'today' && $timeFilter !== 'all') {
            $results = $this->fillMissingDates($results, $startDate, $endDate, $formatType);
        }

        return $results;
    }

    private function fillMissingDates($results, $startDate, $endDate, $formatType)
    {
        $filledResults = collect([]);
        $current = $startDate->copy();
        $format = $formatType === 'day' ? 'Y-m-d' : 'H:00';

        // Create a map of existing dates for fast lookup
        $existingDates = $results->pluck('count', 'date')->toArray();

        // Loop through date range and fill in missing dates with zero counts
        while ($current <= $endDate) {
            $dateKey = $current->format($format);
            
            if (isset($existingDates[$dateKey])) {
                $filledResults->push([
                    'date' => $dateKey,
                    'count' => $existingDates[$dateKey]
                ]);
            } else {
                $filledResults->push([
                    'date' => $dateKey,
                    'count' => 0
                ]);
            }

            // Increment date based on filter
            if ($formatType === 'day') {
                $current->addDay();
            } else {
                $current->addHour();
            }
        }

        return $filledResults;
    }

    public function getStats(Request $request)
    {
        $query = OrderPerbaikan::query();
        $timeFilter = $request->input('time', 'all');
        $statusFilter = $request->input('status', 'all');

        // Time filter
        switch ($timeFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        // Status filter
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Get counts per date - using PostgreSQL date formatting
        $orderCounts = $query->clone()
            ->select(DB::raw("TO_CHAR(created_at, 'YYYY-MM-DD') as date"), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Get status distribution
        $statusDistribution = [
            'pending' => $query->clone()->where('status', 'pending')->count(),
            'reject' => $query->clone()->where('status', 'reject')->count(),
            'konfirmasi' => $query->clone()->where('status', 'konfirmasi')->count()
        ];

        // Generate dates array
        $dates = [];
        if ($timeFilter === 'today') {
            $dates = [Carbon::today()->format('Y-m-d')];
        } elseif ($timeFilter === 'week') {
            for ($i = 0; $i <= 6; $i++) {
                $dates[] = Carbon::now()->startOfWeek()->addDays($i)->format('Y-m-d');
            }
        } elseif ($timeFilter === 'month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $dates[] = Carbon::now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
            }
        } else {
            // For 'all', we use the actual dates in the database
            $datesFromDb = OrderPerbaikan::selectRaw("TO_CHAR(created_at, 'YYYY-MM-DD') as date")
                ->distinct()
                ->orderBy('date')
                ->pluck('date')
                ->toArray();
            $dates = $datesFromDb;
        }

        // Fill in missing dates with 0
        $counts = [];
        foreach ($dates as $date) {
            $counts[] = $orderCounts[$date] ?? 0;
        }

        return response()->json([
            'dates' => $dates,
            'counts' => $counts,
            'statusDistribution' => $statusDistribution,
        ]);
    }

    public function profile()
    {
        return view('administrasi-umum.profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function settings()
    {
        return view('administrasi-umum.settings.index', [
            'user' => Auth::user()
        ]);
    }
} 