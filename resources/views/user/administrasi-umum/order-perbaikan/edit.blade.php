@extends('user.layouts.app')

@section('title', 'Edit Order Perbaikan')

@section('content')
<div class="container mx-auto p-2">
    <div class="bg-white rounded-lg shadow-lg mb-4">
        <!-- Header Section -->
        <div class="bg-gray-50 p-4 rounded-t-lg border-b">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">Edit Order Perbaikan</h1>
                <a href="{{ route('user.administrasi-umum.order-barang') }}"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <form action="{{ route('user.administrasi-umum.order-perbaikan.update', $orderPerbaikan) }}" method="POST"
            class="p-4">
            @csrf
            @method('PUT')

            @if(session('error'))
            <div class="mb-3 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative text-sm">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-3 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Order Information (Read Only) -->
            <div class="mb-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                        <input type="text" value="{{ $orderPerbaikan->nomor }}"
                            class="w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm text-gray-700"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="text" value="{{ $orderPerbaikan->tanggal->format('d/m/Y H:i') }}"
                            class="w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm text-gray-700"
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Editable Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Peminta</label>
                    <input type="text" name="nip_peminta" value="{{ old('nip_peminta', $orderPerbaikan->nip_peminta) }}"
                        class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                        <select name="jenis_barang"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="Inventaris"
                                {{ old('jenis_barang', $orderPerbaikan->jenis_barang) === 'Inventaris' ? 'selected' : '' }}>
                                Inventaris</option>
                            <option value="Non-Inventaris"
                                {{ old('jenis_barang', $orderPerbaikan->jenis_barang) === 'Non-Inventaris' ? 'selected' : '' }}>
                                Non-Inventaris</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                        <select name="prioritas"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="BIASA"
                                {{ old('prioritas', $orderPerbaikan->prioritas) === 'BIASA' ? 'selected' : '' }}>BIASA
                            </option>
                            <option value="SEGERA"
                                {{ old('prioritas', $orderPerbaikan->prioritas) === 'SEGERA' ? 'selected' : '' }}>SEGERA
                            </option>
                            <option value="URGENT"
                                {{ old('prioritas', $orderPerbaikan->prioritas) === 'URGENT' ? 'selected' : '' }}>URGENT
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Inventaris</label>
                        <input type="text" name="kode_inventaris"
                            value="{{ old('kode_inventaris', $orderPerbaikan->kode_inventaris) }}"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang"
                            value="{{ old('nama_barang', $orderPerbaikan->nama_barang) }}"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="lokasi"
                        class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}"
                            {{ old('lokasi', $orderPerbaikan->lokasi) == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                    <textarea name="keluhan" rows="2"
                        class="w-full border border-gray-300 rounded-md shadow-sm py-1.5 px-2 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('keluhan', $orderPerbaikan->keluhan) }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-4 flex justify-end space-x-2">
                <a href="{{ route('user.administrasi-umum.order-barang') }}"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection