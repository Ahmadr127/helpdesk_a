@extends('admin.layouts.app')

@section('title', 'Kelola Tiket')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with Notification -->
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <div class="flex items-center space-x-4">
            <!-- Notification Button -->
            @include('admin.layouts.partials.notifications')

            <!-- History Link -->
            <a href="{{ route('admin.tickets.history.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-150 ease-in-out">
                Lihat Riwayat
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- All Tickets Card -->
        <a href="{{ route('admin.tickets.all') }}"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 cursor-pointer">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4 transition duration-300 hover:bg-blue-200">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium">Semua Tiket</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalTickets }}</h3>
                </div>
            </div>
        </a>

        <!-- Open Tickets Card -->
        <a href="{{ route('admin.tickets.open') }}"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 cursor-pointer">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4 transition duration-300 hover:bg-green-200">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium">Tiket Dibuka</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $openTickets }}</h3>
                </div>
            </div>
        </a>

        <!-- In Progress Tickets Card -->
        <a href="{{ route('admin.tickets.in-progress') }}"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 cursor-pointer">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4 transition duration-300 hover:bg-yellow-200">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium">Dalam Proses</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $inProgressTickets }}</h3>
                </div>
            </div>
        </a>

        <!-- Closed Tickets Card -->
        <a href="{{ route('admin.tickets.closed') }}"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 cursor-pointer">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4 transition duration-300 hover:bg-purple-200">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium">Tiket Ditutup</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $closedTickets }}</h3>
                </div>
            </div>
        </a>
    </div>

    <!-- Priority Groups -->
    <div class="space-y-8">
        <!-- High Priority Tickets -->
        @if($tickets->where('priority', 'high')->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-red-50 border-b border-red-100">
                <h3 class="text-lg font-semibold text-red-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Tiket Prioritas Tinggi
                </h3>
            </div>
            <div class="overflow-x-auto">
                @include('admin.tickets.partials.ticket-table', ['tickets' => $tickets->where('priority', 'high')])
            </div>
        </div>
        @endif

        <!-- Medium Priority Tickets -->
        @if($tickets->where('priority', 'medium')->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-yellow-50 border-b border-yellow-100">
                <h3 class="text-lg font-semibold text-yellow-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Tiket Prioritas Sedang
                </h3>
            </div>
            <div class="overflow-x-auto">
                @include('admin.tickets.partials.ticket-table', ['tickets' => $tickets->where('priority', 'medium')])
            </div>
        </div>
        @endif

        <!-- Low Priority Tickets -->
        @if($tickets->where('priority', 'low')->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <h3 class="text-lg font-semibold text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Tiket Prioritas Rendah
                </h3>
            </div>
            <div class="overflow-x-auto">
                @include('admin.tickets.partials.ticket-table', ['tickets' => $tickets->where('priority', 'low')])
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>
@endsection