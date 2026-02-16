<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Building;
use App\Models\Location;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        // Tampilkan 5 data terbaru untuk setiap model
        $limit = 5;

        $categories = Category::latest()->limit($limit)->get();
        $departments = Department::latest()->limit($limit)->get();
        $buildings = Building::latest()->limit($limit)->get();
        $locations = Location::with('building')->latest()->limit($limit)->get();

        return view('admin.master.index', compact(
            'categories',
            'departments',
            'buildings',
            'locations'
        ));
    }

    public function getData(Request $request, $type)
    {
        $limit = $request->input('limit', 5);
        $status = $request->input('status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Query untuk setiap model
        $query = match($type) {
            'categories' => Category::query(),
            'departments' => Department::query(),
            'buildings' => Building::query(),
            'locations' => Location::with('building'),
        };

        // Terapkan filter
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }
        
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Gunakan limit() sebagai pengganti take() agar lebih fleksibel
        $data = $query->latest()->limit($limit)->get();
        
        // Render partial view
        $html = view("admin.master.partials.{$type}-table", [
            $type => $data
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $data->count() // Tambahkan informasi jumlah data
        ]);
    }

    public function bulkAction(Request $request, $type)
    {
        $validated = $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:' . $type . ',id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $model = match($type) {
            'categories' => Category::class,
            'departments' => Department::class,
            'buildings' => Building::class,
            'locations' => Location::class,
        };

        $items = $model::whereIn('id', $validated['selected']);

        switch($validated['action']) {
            case 'activate':
                $items->update(['status' => true]);
                $message = 'Items activated successfully';
                break;
            case 'deactivate':
                $items->update(['status' => false]);
                $message = 'Items deactivated successfully';
                break;
            case 'delete':
                $items->delete();
                $message = 'Items deleted successfully';
                break;
        }

        return back()->with('success', $message);
    }

    public function updateLimit(Request $request, $type)
    {
        $validated = $request->validate([
            'limit' => 'required|integer|min:5|max:20'
        ]);

        session(["${type}_limit" => $validated['limit']]);
        
        return response()->json(['success' => true]);
    }

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'limit' => 'required|integer|min:5|max:20',
            'status' => 'nullable|in:0,1',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        session([
            'global_limit' => $validated['limit'],
            'global_status' => $validated['status'],
            'global_from_date' => $validated['from_date'],
            'global_to_date' => $validated['to_date'],
        ]);

        return response()->json(['success' => true]);
    }
}