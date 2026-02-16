@extends('admin.layouts.app')

@section('title', 'Ticket History Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.tickets.history.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg shadow hover:from-gray-600 hover:to-gray-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 mb-8">
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Ticket #{{ $ticket->ticket_number }}</h1>
            <p class="text-gray-600">Submitted by {{ $ticket->user->name }} on
                {{ $ticket->created_at->format('d M Y H:i') }}</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Ticket Information</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium">{{ ucfirst($ticket->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category:</span>
                            <span class="font-medium">{{ ucfirst($ticket->category) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium">{{ $ticket->department }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Location:</span>
                            <span class="font-medium">{{ $ticket->location }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Building:</span>
                            <span class="font-medium">{{ $ticket->building }}</span>
                        </div>
                    </div>
                </div>

                @if($ticket->user_confirmed_at)
                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Confirmation Details</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Confirmed At:</span>
                            <span class="font-medium">{{ $ticket->user_confirmed_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($ticket->user_confirmation_notes)
                        <div>
                            <span class="text-gray-600 block mb-1">User Notes:</span>
                            <p class="bg-white p-3 rounded border border-gray-200 text-gray-700">
                                {{ $ticket->user_confirmation_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Timeline</h2>
                <div class="relative border-l-2 border-blue-200 ml-3 pl-6 pb-2 space-y-6">
                    <!-- Created -->
                    <div class="relative">
                        <div
                            class="absolute -left-9 mt-1.5 flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                            <p class="text-gray-700 font-medium">Ticket created by {{ $ticket->user->name }}</p>
                        </div>
                    </div>

                    <!-- In Progress -->
                    @if($ticket->in_progress_at)
                    <div class="relative">
                        <div
                            class="absolute -left-9 mt-1.5 flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-sm text-gray-500">{{ $ticket->in_progress_at->format('d M Y H:i') }}</span>
                            <p class="text-gray-700 font-medium">Ticket marked as in progress</p>
                        </div>
                    </div>
                    @endif

                    <!-- Closed -->
                    @if($ticket->closed_at)
                    <div class="relative">
                        <div
                            class="absolute -left-9 mt-1.5 flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">{{ $ticket->closed_at->format('d M Y H:i') }}</span>
                            <p class="text-gray-700 font-medium">Ticket closed</p>
                        </div>
                    </div>
                    @endif

                    <!-- User confirmed -->
                    @if($ticket->user_confirmed_at)
                    <div class="relative">
                        <div
                            class="absolute -left-9 mt-1.5 flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-green-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-sm text-gray-500">{{ $ticket->user_confirmed_at->format('d M Y H:i') }}</span>
                            <p class="text-gray-700 font-medium">Confirmed by {{ $ticket->user->name }}</p>
                            @if($ticket->user_confirmation_notes)
                            <p class="mt-1 bg-white p-3 rounded border border-gray-200 text-gray-700">
                                {{ $ticket->user_confirmation_notes }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700">{{ $ticket->description }}</p>

                    @if(isset($ticket->initialPhoto) && $ticket->initialPhoto)
                    <div class="mt-4">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Initial Photo</h3>
                        <a href="{{ Storage::url($ticket->initialPhoto->photo_path) }}" target="_blank">
                            <img src="{{ Storage::url($ticket->initialPhoto->photo_path) }}" alt="Initial Ticket Photo"
                                class="max-w-md rounded-lg shadow-sm hover:shadow-md transition-all">
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Processing Time</h2>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                    @php
                    $start = $ticket->created_at;
                    $end = $ticket->user_confirmed_at;
                    $duration = $start->diff($end);

                    $timeString = [];
                    if ($duration->days > 0) {
                    $timeString[] = $duration->days . ' days';
                    }
                    if ($duration->h > 0) {
                    $timeString[] = $duration->h . ' hours';
                    }
                    if ($duration->i > 0) {
                    $timeString[] = $duration->i . ' minutes';
                    }
                    @endphp

                    <p class="text-gray-700">Total time from ticket creation to user confirmation: <span
                            class="font-medium">{{ implode(', ', $timeString) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection