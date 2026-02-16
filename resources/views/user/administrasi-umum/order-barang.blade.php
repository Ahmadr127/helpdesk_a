@extends('user.layouts.app')

@section('title', 'Order Perbaikan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Background with solid light blue and proper bottom padding -->
<div class="min-h-screen bg-gradient-to-r from-green-50 to-blue-50 pb-24">
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6 bg-white rounded-lg p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Perbaikan</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola permintaan perbaikan barang dan peralatan</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('user.administrasi-umum.order-barang.konfirmasi') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Order Dikonfirmasi
                    </a>
                    <a href="{{ route('user.administrasi-umum.order-barang.reject') }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Order Ditolak
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Header with Action Button -->
            <div class="p-6 flex justify-between items-center border-b border-gray-100">
                <div class="flex-1">
                    <!-- Search and Filter Section -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="relative md:col-span-2">
                            <input type="text" id="search" name="search"
                                class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                                placeholder="Cari nomor order, nama barang...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <input type="date" id="start_date" name="start_date"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                                placeholder="Tanggal Mulai">
                        </div>
                        <div>
                            <input type="date" id="end_date" name="end_date"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                                placeholder="Tanggal Akhir">
                        </div>
                        <div class="text-right md:flex md:justify-between md:space-x-2">
                            <button id="filterDate"
                                class="mb-2 md:mb-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out w-full md:w-auto">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            <a href="{{ route('user.administrasi-umum.order-perbaikan.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow transform hover:-translate-y-0.5 w-full md:w-auto justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Order Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Order
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Barang
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="ordersTableBody">
                        @include('user.administrasi-umum.order-perbaikan._table')
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden p-12 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Order</h3>
                <p class="text-gray-500 mb-6">Mulai dengan membuat order perbaikan baru</p>
                <a href="{{ route('user.administrasi-umum.order-perbaikan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Order Baru
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let refreshInterval;

async function refreshOrderList() {
    try {
        const response = await fetch('{{ route("user.administrasi-umum.order-barang") }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) throw new Error('Failed to refresh order list');

        const result = await response.json();
        const tableBody = document.getElementById('ordersTableBody');
        tableBody.innerHTML = result.html;

        // Toggle empty state
        const emptyState = document.getElementById('emptyState');
        const hasOrders = tableBody.querySelector('tr:not([data-empty])');
        emptyState.classList.toggle('hidden', hasOrders);
    } catch (error) {
        console.error('Error refreshing order list:', error);
    }
}

function showOrderDetail(id) {
    window.location.href = `/user/administrasi-umum/order-perbaikan/${id}`;
}

document.addEventListener('DOMContentLoaded', function() {
    refreshInterval = setInterval(refreshOrderList, 30000);
});

window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

// Fungsi pencarian
document.getElementById('search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTableBody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });

    // Toggle empty state berdasarkan hasil pencarian
    const hasVisibleRows = Array.from(rows).some(row => row.style.display !== 'none');
    document.getElementById('emptyState').classList.toggle('hidden', hasVisibleRows);
});

// Fungsi filter tanggal
document.getElementById('filterDate').addEventListener('click', async function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (!startDate || !endDate) {
        alert('Silakan pilih tanggal mulai dan tanggal akhir');
        return;
    }

    try {
        const response = await fetch(
            `{{ route('user.administrasi-umum.order-barang') }}?start_date=${startDate}&end_date=${endDate}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

        if (!response.ok) throw new Error('Gagal memfilter data order');

        const result = await response.json();
        const tableBody = document.getElementById('ordersTableBody');
        tableBody.innerHTML = result.html;

        // Toggle empty state
        const emptyState = document.getElementById('emptyState');
        const hasOrders = tableBody.querySelector('tr:not([data-empty])');
        emptyState.classList.toggle('hidden', hasOrders);
    } catch (error) {
        console.error('Error filtering orders:', error);
        alert('Terjadi kesalahan saat memfilter data');
    }
});
</script>
@endsection