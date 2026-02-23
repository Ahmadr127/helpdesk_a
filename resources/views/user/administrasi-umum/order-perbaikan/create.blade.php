@extends('user.layouts.app')

@section('title', 'Buat Order Perbaikan Baru')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">

        <div class="">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-green-600 to-blue-300 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white">Buat Order Perbaikan Baru</h2>
                    <a href="{{ route('user.administrasi-umum.order-barang') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 bg-opacity-80 rounded-lg text-white hover:bg-opacity-30 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>

                {{-- Flash Messages --}}
                @if(session('error'))
                <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
                @endif

                {{-- Form --}}
                <div class="p-6">
                    <form action="{{ route('user.administrasi-umum.order-perbaikan.store') }}"
                        method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Hidden auto fields --}}
                        <input type="hidden" name="nomor" value="{{ $nomor }}">
                        <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d H:i:s') }}">

                        {{-- Keluhan / Deskripsi --}}
                        <div>
                            <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keluhan</label>
                            <textarea name="keluhan" id="keluhan" rows="4"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('keluhan') border-red-400 @enderror"
                                placeholder="Jelaskan masalah yang Anda alami..."
                                required>{{ old('keluhan') }}</textarea>
                            @error('keluhan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Unit Proses dan Jenis Barang --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" required
                                    value="{{ old('nama_barang') }}"
                                    placeholder="Nama barang yang bermasalah"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama_barang') border-red-400 @enderror">
                                @error('nama_barang')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="jenis_barang" class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                                <select name="jenis_barang" id="jenis_barang" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('jenis_barang') border-red-400 @enderror">
                                    <option value="">Pilih Jenis Barang</option>
                                    <option value="Inventaris" {{ old('jenis_barang') === 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                                    <option value="Umum" {{ old('jenis_barang') === 'Umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                                @error('jenis_barang')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Lokasi dan Prioritas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                <select name="lokasi" id="lokasi" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('lokasi') border-red-400 @enderror">
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('lokasi') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('lokasi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                                <select name="prioritas" id="prioritas" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('prioritas') border-red-400 @enderror">
                                    <option value="">Pilih Prioritas</option>
                                    <option value="RENDAH" {{ old('prioritas') === 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                                    <option value="SEDANG" {{ old('prioritas') === 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                                    <option value="TINGGI/URGENT" {{ old('prioritas') === 'TINGGI/URGENT' ? 'selected' : '' }}>Tinggi / Urgent</option>
                                </select>
                                @error('prioritas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Barang dan Foto --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit_proses_select" class="block text-sm font-medium text-gray-700 mb-1">Unit Proses</label>
                                <select name="unit_proses_code" id="unit_proses_select" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('unit_proses_code') border-red-400 @enderror">
                                    <option value="">Pilih Unit Proses</option>
                                    @foreach($unitProses as $unit)
                                    <option value="{{ $unit->code }}" data-name="{{ $unit->name }}"
                                        {{ old('unit_proses_code') === $unit->code ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="unit_proses" id="unit_proses">
                                <input type="hidden" name="unit_proses_name" id="unit_proses_name">
                                @error('unit_proses_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto <span class="text-gray-400">(opsional)</span></label>
                                <div class="flex items-center">
                                    <label class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span id="foto_filename" class="text-sm text-gray-700">Pilih Foto</span>
                                        <input id="foto" name="foto" type="file" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <p id="foto-name" class="mt-1 text-xs text-gray-500">Ukuran file maksimum: 5MB</p>
                                <div id="preview-container" class="hidden mt-2">
                                    <img id="preview-image" src="#" alt="Preview"
                                        class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                </div>
                                @error('foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('user.administrasi-umum.order-barang') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                Buat Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Sync unit proses hidden fields on change
document.getElementById('unit_proses_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('unit_proses').value = selectedOption.value || '';
    document.getElementById('unit_proses_name').value = selectedOption.getAttribute('data-name') || '';
});

// Trigger on load in case old() repopulates it
(function() {
    const sel = document.getElementById('unit_proses_select');
    if (sel && sel.value) {
        sel.dispatchEvent(new Event('change'));
    }
})();

// Foto preview
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const filenameSpan = document.getElementById('foto_filename');
    const fotoName = document.getElementById('foto-name');
    if (file) {
        filenameSpan.textContent = file.name;
        fotoName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        filenameSpan.textContent = 'Pilih Foto';
        fotoName.textContent = 'Ukuran file maksimum: 5MB';
        document.getElementById('preview-container').classList.add('hidden');
    }
});
</script>
@endsection
