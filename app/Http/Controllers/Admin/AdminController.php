<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $data = [
                'totalTickets' => \App\Models\Ticket::count(),
                'openTickets' => \App\Models\Ticket::where('status', 'open')->count(),
                'inProgressTickets' => \App\Models\Ticket::where('status', 'in_progress')->count(),
                'closedTickets' => \App\Models\Ticket::where('status', 'closed')->count(),
                'recentTickets' => \App\Models\Ticket::with('user')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
                'recentUsers' => \App\Models\User::latest()
                                    ->take(5)
                                    ->get()
            ];

            return view('admin.dashboard.index', $data);
        } catch (\Exception $e) {
            $data = [
                'totalTickets' => \App\Models\Ticket::count(),
                'openTickets' => \App\Models\Ticket::where('status', 'open')->count(),
                'inProgressTickets' => \App\Models\Ticket::where('status', 'in_progress')->count(),
                'closedTickets' => \App\Models\Ticket::where('status', 'closed')->count(),
                'recentTickets' => \App\Models\Ticket::with('user')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
                'recentUsers' => \App\Models\User::latest()
                                    ->take(5)
                                    ->get()
            ];

            return view('admin.dashboard.index', $data);
        }
    }

    public function users()
    {
        return view('admin.users.index');
    }

    public function tickets()
    {
        return view('admin.tickets.index');
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function getStats(Request $request)
    {
        $query = Ticket::query();
        $timeFilter = $request->input('time', 'all');
        $statusFilter = $request->input('status', 'all');

        // Create base query for time filtering
        $baseTimeQuery = function($q) use ($timeFilter) {
            switch ($timeFilter) {
                case 'today':
                    $q->whereDate('created_at', today());
                    break;
                case 'week':
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $q->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                    break;
                default:
                    // For 'all' time, don't apply any time filter
                    break;
            }
        };

        // Create base query for status filtering
        $baseStatusQuery = function($q) use ($statusFilter) {
            if ($statusFilter !== 'all') {
                if ($statusFilter === 'closed') {
                    $q->where('status', 'closed')
                      ->where('user_confirmation', false);
                } elseif ($statusFilter === 'confirmed') {
                    $q->where('status', 'confirmed');
                } else {
                    $q->where('status', $statusFilter);
                }
            }
        };

        // Apply filters to main query
        $baseTimeQuery($query);
        $baseStatusQuery($query);

        // Get counts per date
        $ticketCounts = $query->clone()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Get status distribution with both filters
        $statusDistribution = [
            'open' => Ticket::query()
                ->tap($baseTimeQuery)
                ->tap($baseStatusQuery)
                ->where('status', 'open')
                ->count(),
            'in_progress' => Ticket::query()
                ->tap($baseTimeQuery)
                ->tap($baseStatusQuery)
                ->where('status', 'in_progress')
                ->count(),
            'pending' => Ticket::query()
                ->tap($baseTimeQuery)
                ->tap($baseStatusQuery)
                ->where('status', 'pending')
                ->count(),
            'closed' => Ticket::query()
                ->tap($baseTimeQuery)
                ->tap($baseStatusQuery)
                ->where('status', 'closed')
                ->where('user_confirmation', false)
                ->count(),
            'confirmed' => Ticket::query()
                ->tap($baseTimeQuery)
                ->tap($baseStatusQuery)
                ->where('status', 'confirmed')
                ->count()
        ];

        // Generate dates array based on time filter
        switch ($timeFilter) {
            case 'today':
                $dates = [today()->format('Y-m-d')];
                break;
            case 'week':
                $dates = collect(range(0, 6))->map(fn($day) => now()->startOfWeek()->addDays($day)->format('Y-m-d'));
                break;
            case 'month':
                $dates = collect(range(1, now()->daysInMonth))
                    ->map(fn($day) => now()->startOfMonth()->addDays($day - 1)->format('Y-m-d'));
                break;
            default:
                // For 'all' time, get all unique dates from the database
                $dates = Ticket::selectRaw('DISTINCT DATE(created_at) as date')
                    ->orderBy('date')
                    ->pluck('date')
                    ->toArray();
        }

        // Fill in missing dates with 0
        $counts = collect($dates)->map(fn($date) => $ticketCounts[$date] ?? 0)->toArray();

        return response()->json([
            'dates' => $dates,
            'counts' => $counts,
            'statusDistribution' => $statusDistribution,
        ]);
    }
} 