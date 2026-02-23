@extends('user.layouts.app')

@section('title', 'Order Perbaikan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Latar belakang dengan warna biru muda solid dan padding bawah yang sesuai -->
<div class="min-h-screen bg-gradient-to-r from-green-50 to-blue-50 pb-24">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Halaman dengan Filter -->
        <div class="mb-6 bg-white bg-gradient-to-r from-green-600 to-blue-300 rounded-lg p-6 shadow-sm">
            <div class="flex flex-col space-y-4">
                <!-- Judul dan Tombol Aksi -->
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Order Perbaikan</h1>
                        <p class="mt-2 text-sm text-white">Kelola permintaan perbaikan barang dan peralatan</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.administrasi-umum.order-barang.konfirmasi') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
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
                        <a href="{{ route('user.administrasi-umum.order-perbaikan.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out shadow-sm hover:shadow transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Order Baru
                        </a>
                    </div>
                </div>

                <!-- Bagian Pencarian dan Filter -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative md:col-span-2">
                        <input type="text" id="search" name="search"
                            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                            placeholder="Cari nomor order, nama barang...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                </div>
            </div>
        </div>

        <!-- In Progress Orders Cards -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Order Dalam Proses</h2>
                <div class="flex space-x-2">
                    <button onclick="scrollCards('left')"
                        class="p-2 rounded-full bg-white shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button onclick="scrollCards('right')"
                        class="p-2 rounded-full bg-white shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="relative">
                <div id="cardsContainer" class="flex overflow-x-auto pb-2 space-x-4 scrollbar-hide scroll-smooth">
                    @include('user.administrasi-umum.order-perbaikan._in_progress_cards', ['inProgressOrders' =>
                    $inProgressOrders])
                </div>
            </div>
        </div>

        <!-- Open Orders Table Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Table Header Card -->
            <div class="bg-gradient-to-r from-green-600 to-blue-200 p-4 rounded-t-lg">
                <h2 class="text-xl font-semibold text-white">Daftar Order</h2>
                <p class="text-sm text-white opacity-80">Kelola dan pantau status order Anda</p>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Order
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Kategori / Dept
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Keluhan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm" id="ordersTableBody">
                        @include('user.administrasi-umum.order-perbaikan._table', ['orders' => $orders])
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



<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

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

        if (!response.ok) throw new Error('Gagal memperbarui daftar order');

        const result = await response.json();
        const tableBody = document.getElementById('ordersTableBody');
        tableBody.innerHTML = result.html;

        const emptyState = document.getElementById('emptyState');
        const hasOrders = tableBody.querySelector('tr:not([data-empty])');
        emptyState.classList.toggle('hidden', hasOrders);
    } catch (error) {
        console.error('Kesalahan memperbarui daftar order:', error);
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

document.getElementById('search').addEventListener('input', debounce(function(e) {
    filterOrders();
}, 300));

document.getElementById('start_date').addEventListener('change', filterOrders);
document.getElementById('end_date').addEventListener('change', filterOrders);

async function filterOrders() {
    const searchTerm = document.getElementById('search').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    try {
        const response = await fetch(
            `{{ route('user.administrasi-umum.order-barang') }}?search=${searchTerm}&start_date=${startDate}&end_date=${endDate}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

        if (!response.ok) throw new Error('Gagal memfilter data order');

        const result = await response.json();

        const tableBody = document.getElementById('ordersTableBody');
        tableBody.innerHTML = result.html;

        const cardsContainer = document.getElementById('cardsContainer');
        cardsContainer.innerHTML = result.inProgressHtml;

        const emptyState = document.getElementById('emptyState');
        const hasOrders = tableBody.querySelector('tr:not([data-empty])');
        emptyState.classList.toggle('hidden', hasOrders);
    } catch (error) {
        console.error('Kesalahan saat memfilter order:', error);
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const filenameSpan = document.getElementById('foto_filename');
    if (file) {
        filenameSpan.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        filenameSpan.textContent = 'Belum ada foto dipilih';
        document.getElementById('preview-container').classList.add('hidden');
    }
});

function scrollCards(direction) {
    const container = document.getElementById('cardsContainer');
    const scrollAmount = 320;

    if (direction === 'left') {
        container.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    } else {
        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
}


</script>
@endsection