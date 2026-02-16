@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-500 rounded-full">
                <i class="fas fa-ticket-alt text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Total Tickets</h3>
                <p class="text-2xl font-semibold">{{ \App\Models\Ticket::count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-500 rounded-full">
                <i class="fas fa-ticket-alt text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Open Tickets</h3>
                <p class="text-2xl font-semibold">
                    {{ \App\Models\Ticket::where('status', 'open')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-500 rounded-full">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">In Progress Tickets</h3>
                <p class="text-2xl font-semibold">{{ \App\Models\Ticket::where('status', 'in_progress')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-500 rounded-full">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Closed & Confirmed Tickets</h3>
                <p class="text-2xl font-semibold">
                    {{ \App\Models\Ticket::whereIn('status', ['closed', 'confirmed'])->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Ticket Statistics</h2>
            <div class="flex space-x-2">
                <select id="timeFilter"
                    class="text-sm rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
                <select id="statusFilter"
                    class="text-sm rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="all">All Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="pending">Pending</option>
                    <option value="closed">Closed</option>
                    <option value="confirmed">Confirmed</option>
                </select>
            </div>
        </div>
        <div class="h-64">
            <canvas id="ticketChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Status Distribution</h2>
        </div>
        <div class="h-64">
            <canvas id="statusPieChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Tickets</h2>
            <a href="{{ route('admin.tickets.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b text-left">ID</th>
                        <th class="px-6 py-3 border-b text-left">User</th>
                        <th class="px-6 py-3 border-b text-left">Status</th>
                        <th class="px-6 py-3 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTickets as $ticket)
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $ticket->ticket_number }}</td>
                        <td class="px-6 py-4 border-b">{{ $ticket->user->name }}</td>
                        <td class="px-6 py-4 border-b">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($ticket->status === 'closed' ? 'bg-red-100 text-red-800' : 
                                   ($ticket->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                   ($ticket->status === 'open' ? 'bg-blue-200 text-blue-800' :
                                   'bg-purple-100 text-purple-800'))) }}">
                                {{ ucfirst($ticket->status) }}
                                {{ $ticket->status === 'closed' ? '(Waiting for user confirmation)' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                                class="text-blue-600 hover:text-blue-900 text-sm">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Users</h2>
            <a href="{{ route('admin.users.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b text-left">Name</th>
                        <th class="px-6 py-3 border-b text-left">Email</th>
                        <th class="px-6 py-3 border-b text-left">Joined</th>
                        <th class="px-6 py-3 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $user->name }}</td>
                        <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                        <td class="px-6 py-4 border-b">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 border-b">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-blue-600 hover:text-blue-900 text-sm">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let ticketChart;
let statusPieChart;

function initCharts() {
    // Line chart for ticket trends
    const ticketCtx = document.getElementById('ticketChart').getContext('2d');
    ticketChart = new Chart(ticketCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Tickets',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.1)'
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

    // Pie chart for status distribution
    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    statusPieChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Pending', 'Closed', 'Confirmed'],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgb(54, 162, 235)', // Blue for Open
                    'rgb(255, 206, 86)', // Yellow for In Progress
                    'rgb(255, 159, 64)', // Orange for Pending
                    'rgb(255, 99, 132)', // Red for Closed
                    'rgb(75, 192, 192)' // Green for Confirmed
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
                        boxWidth: 12,
                        padding: 15
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function updateCharts() {
    const timeFilter = document.getElementById('timeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;

    fetch(`/admin/dashboard/stats?time=${timeFilter}&status=${statusFilter}`)
        .then(response => response.json())
        .then(data => {
            // Update line chart
            ticketChart.data.labels = data.dates.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
            });
            ticketChart.data.datasets[0].data = data.counts;
            ticketChart.update();

            // Update pie chart
            statusPieChart.data.datasets[0].data = [
                data.statusDistribution.open,
                data.statusDistribution.in_progress,
                data.statusDistribution.pending,
                data.statusDistribution.closed,
                data.statusDistribution.confirmed
            ];
            statusPieChart.update();
        });
}

document.addEventListener('DOMContentLoaded', () => {
    initCharts();
    updateCharts();

    document.getElementById('timeFilter').addEventListener('change', updateCharts);
    document.getElementById('statusFilter').addEventListener('change', updateCharts);
});
</script>
@endpush