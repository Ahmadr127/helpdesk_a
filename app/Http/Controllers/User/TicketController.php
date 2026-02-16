<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\Category;
use App\Models\Building;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketConfirmedNotification;
use App\Models\User;
use App\Notifications\TicketRejectedNotification;
use App\Models\TicketPhoto;
use App\Notifications\TicketRespondedNotification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categories = Category::where('status', 1)->get();
        
        return view('user.ticket.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        // Get only active master data
        $categories = Category::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();
        $buildings = Building::where('status', 1)->get();
        $locations = Location::where('status', 1)->with('building')->get();

        return view('user.ticket.create', compact('categories', 'departments', 'buildings', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'building_id' => 'required|exists:buildings,id',
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'photo' => 'nullable|image|max:5120', // 5MB max, optional
        ]);

        // Generate ticket number
        $ticketNumber = 'TIK-' . date('Ymd') . '-' . rand(1000, 9999);

        // Get display names from related models
        $category = Category::find($validated['category_id']);
        $department = Department::find($validated['department_id']);
        $building = Building::find($validated['building_id']);
        $location = Location::find($validated['location_id']);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'category' => $category->name,
            'department_id' => $validated['department_id'],
            'department' => $department->name,
            'building_id' => $validated['building_id'],
            'building' => $building->name,
            'location_id' => $validated['location_id'],
            'location' => $location->name,
            'priority' => $validated['priority'],
            'status' => 'open'
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ticket-photos', 'public');
            
            // Create ticket photo record
            TicketPhoto::create([
                'ticket_id' => $ticket->id,
                'photo_path' => $path,
                'type' => 'initial'
            ]);
        }

        return redirect()->route('user.ticket.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.ticket.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        // Check if user owns the ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if ticket is still editable (open status)
        if ($ticket->status !== 'open') {
            return redirect()->route('user.ticket.show', $ticket)
                ->with('error', 'Ticket can only be edited when in open status.');
        }

        // Get only active master data
        $categories = Category::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();
        $buildings = Building::where('status', 1)->get();
        $locations = Location::where('status', 1)->with('building')->get();

        return view('user.ticket.edit', compact('ticket', 'categories', 'departments', 'buildings', 'locations'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Check if user owns the ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if ticket is still editable (open status)
        if ($ticket->status !== 'open') {
            return redirect()->route('user.ticket.show', $ticket)
                ->with('error', 'Ticket can only be edited when in open status.');
        }

        $validated = $request->validate([
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'building_id' => 'required|exists:buildings,id',
            'location_id' => 'required|exists:locations,id',
            'priority' => 'required|in:low,medium,high'
        ]);

        try {
            // Get related models
            $category = Category::findOrFail($validated['category_id']);
            $department = Department::findOrFail($validated['department_id']);
            $building = Building::findOrFail($validated['building_id']);
            $location = Location::findOrFail($validated['location_id']);

            $ticket->update([
                'description' => $validated['description'],
                // Store both ID and text value for backward compatibility
                'category_id' => $validated['category_id'],
                'category' => $category->name,
                'department_id' => $validated['department_id'],
                'department' => $department->name,
                'building_id' => $validated['building_id'],
                'building' => $building->name,
                'location_id' => $validated['location_id'],
                'location' => $location->name,
                'priority' => $validated['priority']
            ]);

            return redirect()->route('user.ticket.show', $ticket)
                ->with('success', 'Ticket has been updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Ticket update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update ticket: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateStatus(Ticket $ticket, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,open,in_progress,closed'
        ]);

        $timestamp_field = null;
        switch($validated['status']) {
            case 'open':
                $timestamp_field = 'opened_at';
                break;
            case 'in_progress':
                $timestamp_field = 'in_progress_at';
                break;
            case 'closed':
                $timestamp_field = 'closed_at';
                break;
        }

        $ticket->update([
            'status' => $validated['status'],
            $timestamp_field => now()
        ]);

        return back()->with('success', 'Ticket status updated successfully.');
    }

    public function confirm(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'confirmation_notes' => 'required|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $replies = $ticket->user_replies ? json_decode($ticket->user_replies, true) : [];
        
        $reply = [
            'type' => $request->input('action'), // 'confirm' or 'reject'
            'notes' => $validated['confirmation_notes'],
            'timestamp' => now()->toDateTimeString(),
        ];

        // Save response photo if exists
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ticket-responses', 'public');
            TicketPhoto::create([
                'ticket_id' => $ticket->id,
                'photo_path' => $path,
                'type' => $request->input('action') === 'confirm' ? 'user_response' : 'user_rejection'
            ]);
        }

        $replies[] = $reply;
        $ticket->user_replies = json_encode($replies);

        if ($request->input('action') === 'confirm') {
            $ticket->update([
                'status' => 'confirmed',
                'user_confirmation' => true,
                'user_confirmed_at' => now(),
            ]);

            // Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TicketRespondedNotification(
                    $ticket,
                    auth()->user(),
                    "User has confirmed ticket #{$ticket->ticket_number} as completed",
                    false,
                    'confirmed'
                ));
            }
        } else {
            $ticket->update([
                'status' => 'in_progress',
                'user_confirmation' => false,
                'rejection_count' => $ticket->rejection_count + 1,
                'last_rejection_at' => now(),
            ]);

            // Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TicketRespondedNotification(
                    $ticket,
                    auth()->user(),
                    "User has rejected ticket #{$ticket->ticket_number}",
                    false,
                    'rejected'
                ));
            }
        }

        $ticket->save();

        return redirect()->route('user.ticket.show', $ticket)
            ->with('success', $request->input('action') === 'confirm' ? 
                'Ticket has been confirmed as completed.' : 
                'Ticket has been returned to in progress status.');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // Validate ownership
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'photo' => 'nullable|image|max:5120', // 5MB max
        ]);

        $reply = [
            'message' => $validated['message'],
            'timestamp' => now(),
        ];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ticket-replies', 'public');
            $reply['photo'] = $path;
        }

        // Get existing replies or create new array
        $replies = $ticket->user_replies ? json_decode($ticket->user_replies, true) : [];
        $replies[] = $reply;

        $ticket->update([
            'user_replies' => json_encode($replies)
        ]);

        // Send notification to all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TicketRespondedNotification(
                $ticket,
                auth()->user(),
                "User has replied to ticket #{$ticket->ticket_number}",
                false,
                'replied'
            ));
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function filterByStatus(Request $request, $status)
    {
        $query = Ticket::where('user_id', auth()->id());

        // If status is 'all', don't apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Time filter
        $timeFilter = $request->input('time_filter', 'all');
        $month = $request->input('month');
        
        switch ($timeFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                if ($month) {
                    $query->whereYear('created_at', now()->year)
                          ->whereMonth('created_at', $month);
                } else {
                    $query->whereMonth('created_at', now()->month);
                }
                break;
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::where('status', 1)->get();

        return view('user.ticket.filtered', compact('tickets', 'categories', 'status', 'timeFilter', 'month'));
    }

    public function destroy(Ticket $ticket)
    {
        // Check if user owns the ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if ticket can be deleted (only open tickets)
        if ($ticket->status !== 'open') {
            return redirect()->route('user.ticket.show', $ticket)
                ->with('error', 'Only open tickets can be deleted.');
        }

        try {
            $ticket->delete();
            return redirect()->route('user.dashboard')
                ->with('success', 'Ticket has been deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Ticket deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete ticket.']);
        }
    }
} 