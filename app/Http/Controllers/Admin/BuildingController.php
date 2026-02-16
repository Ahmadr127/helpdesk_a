<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        $query = Building::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $buildings = $query->latest()->paginate(10)->withQueryString();
        return view('admin.master.buildings', compact('buildings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings',
            'status' => 'required|boolean',
        ]);

        Building::create($validated);
        return redirect()->route('admin.master.buildings.index')->with('success', 'Building created successfully');
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'status' => 'required|boolean',
        ]);

        $building->update($validated);
        return redirect()->route('admin.master.buildings.index')->with('success', 'Building updated successfully');
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return redirect()->route('admin.master.buildings.index')->with('success', 'Building deleted successfully');
    }
} 