@extends('administrasi-umum.layouts.app')

@section('title', 'Administrasi Umum Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrasi Umum</h1>
        <p class="text-gray-600">Overview perbaikan dan pemeliharaan</p>
    </div>

        <!-- Combined Filter and Charts Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-3">
                    <select id="timeFilter" name="time_filter"
                        class="rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 bg-white">
                        <option value="all" {{ $timeFilter === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $timeFilter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $timeFilter === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $timeFilter === 'month' ? 'selected' : '' }}>This Month</option>
                    </select>

                    <select id="statusFilter" name="status_filter"
                        class="rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 bg-white">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reject" {{ $statusFilter === 'reject' ? 'selected' : '' }}>Reject</option>
                        <option value="konfirmasi" {{ $statusFilter === 'konfirmasi' ? 'selected' : '' }}>Konfirmasi</option>
                    </select>
                </div>
                </div>
            </div>

            <!-- Charts Section -->
        <div class="p-5">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Order Trends Chart -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Order Trends</h3>
                    <div class="bg-white rounded-lg p-4 shadow-inner" style="height: 300px;">
                        <canvas id="orderTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Status Distribution Chart -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-4 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Status Distribution</h3>
                    <div class="bg-white rounded-lg p-4 shadow-inner" style="height: 300px;">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Orders Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full shadow">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-500"></div>
            </div>

            <!-- Pending Orders Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full shadow">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Pending</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-yellow-400 to-yellow-500"></div>
            </div>

            <!-- Reject Orders Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-red-400 to-red-500 rounded-full shadow">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Reject</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $rejectedOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-red-400 to-red-500"></div>
            </div>

            <!-- Konfirmasi Orders Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-green-400 to-green-500 rounded-full shadow">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Konfirmasi</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $confirmedOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-400 to-green-500"></div>
            </div>
        </div>

        <!-- Recent Orders Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-5 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Recent Orders</h3>
                <a href="{{ route('administrasi-umum.order-perbaikan.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-sm font-medium rounded-lg text-white shadow hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all group">
                    View all orders ({{ $totalRecentOrders }})
                    <svg class="ml-2 -mr-1 h-5 w-5 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($recentOrders as $order)
                <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $order->nomor }}
                            </div>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->status === 'konfirmasi') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                @elseif($order->status === 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                @else bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="text-sm text-gray-900 font-medium mb-1">{{ $order->nama_barang }}</div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $order->creator->name }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->prioritas === 'URGENT') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                @elseif($order->prioritas === 'SEGERA') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                @else bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                @endif">
                                {{ $order->prioritas }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <div class="text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y') }}
                                </div>
                                <a href="{{ route('administrasi-umum.order-perbaikan.show', $order) }}"
                                    class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-indigo-500 to-indigo-600 text-sm font-medium rounded-md text-white shadow hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all group">
                                    Detail
                                    <svg class="ml-1.5 -mr-1 h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('timeFilter').addEventListener('change', function() {
    updateDashboard();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    updateDashboard();
});

function updateDashboard() {
    const timeFilter = document.getElementById('timeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    window.location.href = `{{ route('administrasi-umum.dashboard') }}?time_filter=${timeFilter}&status_filter=${statusFilter}`;
}

// Order Trends Chart
const orderTrendsCtx = document.getElementById('orderTrendsChart').getContext('2d');
new Chart(orderTrendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($orderTrends->pluck('date')) !!},
        datasets: [{
            label: 'Orders',
            data: {!! json_encode($orderTrends->pluck('count')) !!},
            borderColor: 'rgb(59, 130, 246)',
            tension: 0.3,
            fill: true,
            backgroundColor: 'rgba(59, 130, 246, 0.1)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
            }
        },
            x: {
                grid: {
                display: false
                }
            }
        }
    }
});

// Status Distribution Chart
const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
new Chart(statusDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Reject', 'Konfirmasi'],
        datasets: [{
            data: [
                {{ $statusDistribution['pending'] ?? 0 }},
                {{ $statusDistribution['reject'] ?? 0 }},
                {{ $statusDistribution['konfirmasi'] ?? 0 }}
            ],
            backgroundColor: [
                'rgb(234, 179, 8)',  // Yellow for Pending
                'rgb(239, 68, 68)',  // Red for Reject
                'rgb(34, 197, 94)'   // Green for Konfirmasi
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    usePointStyle: true,
                    padding: 15
                }
            }
        },
        cutout: '65%'
    }
});
</script>
@endpush
@endsection