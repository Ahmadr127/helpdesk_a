@extends('user.layouts.app')

@section('title', 'Detail Order Perbaikan')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header Section -->
        <div class="bg-gray-50 p-6 rounded-t-lg border-b">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">Detail Order Perbaikan</h1>
                <div class="flex space-x-3">
                    @if($order->status === 'pending')
                    <a href="{{ route('user.administrasi-umum.order-perbaikan.edit', $order) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </a>
                    @endif
                    <a href="{{ route('user.administrasi-umum.order-barang') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="p-6">
            <!-- Main Info Grid -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Informasi Order</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Order</p>
                            <p class="font-medium">{{ $order->nomor }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-medium">{{ $order->tanggal->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $order->getStatusBadgeClass() }}">
                                {{ $order->getStatusText() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Prioritas</p>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                {{ $order->prioritas === 'URGENT' ? 'bg-red-100 text-red-800' : 
                                   ($order->prioritas === 'SEGERA' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-green-100 text-green-800') }}">
                                {{ $order->prioritas }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Informasi Unit</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Unit Proses</p>
                            <p class="font-medium">{{ $order->unit_proses }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Unit Penerima</p>
                            <p class="font-medium">{{ $order->unit_penerima }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">NIP Peminta</p>
                            <p class="font-medium">{{ $order->nip_peminta }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barang Info -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Barang</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Jenis Barang</p>
                        <p class="font-medium">{{ $order->jenis_barang }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kode Inventaris</p>
                        <p class="font-medium">{{ $order->kode_inventaris }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Barang</p>
                        <p class="font-medium">{{ $order->nama_barang }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-medium">{{ $order->location->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Keluhan -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4">Keluhan</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $order->keluhan }}</p>
            </div>

            <!-- Admin Response Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4">Respon Admin</h3>
                @if($order->status !== 'pending')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Status Respon</p>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $order->getStatusBadgeClass() }}">
                                {{ $order->getStatusText() }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Penanggung Jawab</p>
                            <p class="font-medium">{{ $order->nama_penanggung_jawab ?? '-' }}</p>
                        </div>
                        @if($order->follow_up)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Tindak Lanjut</p>
                            <p class="font-medium mt-1">{{ $order->follow_up }}</p>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="text-gray-400 mb-2">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Menunggu Respon</h3>
                        <p class="text-gray-500">Admin belum memberikan respon untuk order ini</p>
                    </div>
                @endif
            </div>

            <!-- Order History -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Riwayat Order</h3>
                <div class="space-y-4">
                    @foreach($order->history()->latest()->get() as $history)
                    <div class="border-l-4 
                        {{ $history->status === 'pending' ? 'border-yellow-400' : 
                           ($history->status === 'konfirmasi' ? 'border-green-400' : 
                           'border-red-400') }} 
                        pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                    {{ $history->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($history->status === 'konfirmasi' ? 'bg-green-100 text-green-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($history->status) }}
                                </span>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium">Tindak Lanjut:</span> {{ $history->follow_up }}
                                </p>
                            </div>
                            <div class="text-right text-sm text-gray-500">
                                <p>{{ $history->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $history->creator->name }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection