@extends('user.layouts.app')

@section('title', 'Edit Order Perbaikan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto max-w-6xl p-2 mt-6">
    <div class="bg-white rounded-lg shadow-lg mb-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-400 to-blue-300 p-3 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-white">Edit Order Perbaikan</h1>
                <a href="{{ route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('user.administrasi-umum.order-perbaikan.update', $orderPerbaikan) }}"
            method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
            @csrf
            @method('PUT')

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Info Readonly -->
            <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                    <input type="text" value="{{ $orderPerbaikan->nomor }}"
                        class="w-full bg-gray-100 border border-gray-300 rounded-md py-1.5 px-2 text-sm text-gray-700" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="text" value="{{ $orderPerbaikan->tanggal->format('d/m/Y H:i') }}"
                        class="w-full bg-gray-100 border border-gray-300 rounded-md py-1.5 px-2 text-sm text-gray-700" readonly>
                </div>
            </div>

            <!-- Keluhan -->
            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keluhan</label>
                <textarea name="keluhan" id="keluhan" rows="3"
                    class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500 @error('keluhan') border-red-400 @enderror"
                    required>{{ old('keluhan', $orderPerbaikan->keluhan) }}</textarea>
                @error('keluhan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Kategori & Departemen -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" id="category_id"
                        class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500 @error('category_id') border-red-400 @enderror"
                        required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $orderPerbaikan->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <div class="flex items-center px-2 py-1.5 rounded-md border border-gray-200 bg-gray-50">
                        <p class="text-sm text-gray-700 flex-1">{{ $userDepartment?->name ?? '-' }}</p>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="hidden" name="department_id" value="{{ $userDepartment?->id }}">
                </div>
            </div>

            <!-- Lokasi & Gedung -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="lokasi" id="lokasi"
                        class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500 @error('lokasi') border-red-400 @enderror"
                        required onchange="updateBuilding()">
                        <option value="">Pilih Lokasi</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}"
                            data-building-id="{{ $location->building?->id }}"
                            data-building-name="{{ $location->building?->name }}"
                            {{ old('lokasi', $orderPerbaikan->lokasi) == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('lokasi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gedung</label>
                    <div class="flex items-center px-2 py-1.5 rounded-md border border-gray-200 bg-gray-50">
                        <p id="building_display" class="text-sm text-gray-700 flex-1">Pilih lokasi terlebih dahulu</p>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <input type="hidden" name="building_id" id="building_id_hidden">
                </div>
            </div>

            <!-- Prioritas -->
            <div>
                <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="prioritas" id="prioritas"
                    class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500"
                    required>
                    <option value="">Pilih Prioritas</option>
                    <option value="RENDAH" {{ old('prioritas', $orderPerbaikan->prioritas) === 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                    <option value="SEDANG" {{ old('prioritas', $orderPerbaikan->prioritas) === 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                    <option value="TINGGI/URGENT" {{ old('prioritas', $orderPerbaikan->prioritas) === 'TINGGI/URGENT' ? 'selected' : '' }}>Tinggi / Urgent</option>
                </select>
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-100 p-2 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Foto</h4>
                        <div class="bg-white rounded-lg overflow-hidden flex items-center justify-center h-40">
                            <img id="preview-image"
                                src="{{ $orderPerbaikan->foto ? Storage::url($orderPerbaikan->foto) : '' }}"
                                alt="Preview" class="w-full h-40 object-contain {{ $orderPerbaikan->foto ? '' : 'hidden' }}">
                            <p id="no-photo-text" class="text-sm text-gray-500 {{ $orderPerbaikan->foto ? 'hidden' : '' }}">Tidak ada foto</p>
                        </div>
                    </div>
                    <div class="bg-gray-100 p-2 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Upload Foto Baru</h4>
                        <div class="mt-1 flex justify-center px-4 py-4 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <label for="foto" class="cursor-pointer text-sm text-blue-600 hover:text-blue-500">
                                    <span>Upload foto</span>
                                    <input id="foto" name="foto" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                </label>
                                <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Barang Opsional -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <button type="button" onclick="toggleInfoBarang()"
                    class="flex items-center justify-between w-full text-left text-sm font-medium text-gray-700">
                    <span>Informasi Barang <span class="text-gray-400 font-normal">(opsional)</span></span>
                    <svg id="toggleIcon" class="w-4 h-4 text-gray-400 transform transition-transform {{ $orderPerbaikan->nama_barang || $orderPerbaikan->jenis_barang || $orderPerbaikan->unit_proses ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="infoBarangSection" class="{{ $orderPerbaikan->nama_barang || $orderPerbaikan->jenis_barang || $orderPerbaikan->unit_proses ? '' : 'hidden' }} mt-3 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $orderPerbaikan->nama_barang) }}"
                                class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                            <select name="jenis_barang" class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Jenis</option>
                                <option value="Umum" {{ old('jenis_barang', $orderPerbaikan->jenis_barang) === 'Umum' ? 'selected' : '' }}>Umum</option>
                                <option value="Inventaris" {{ old('jenis_barang', $orderPerbaikan->jenis_barang) === 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Inventaris</label>
                            <input type="text" name="kode_inventaris" value="{{ old('kode_inventaris', $orderPerbaikan->kode_inventaris) }}"
                                class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Proses</label>
                            <select name="unit_proses_code" class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Unit Proses</option>
                                @foreach($unitProses as $unit)
                                <option value="{{ $unit->code }}" {{ old('unit_proses_code', $orderPerbaikan->unit_proses) === $unit->code ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2 pt-2">
                <a href="{{ route('user.administrasi-umum.order-perbaikan.show', $orderPerbaikan) }}"
                    class="px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const previewImage = document.getElementById('preview-image');
    const noPhotoText = document.getElementById('no-photo-text');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.classList.remove('hidden');
            noPhotoText.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateBuilding() {
    const locationSelect = document.getElementById('lokasi');
    const buildingDisplay = document.getElementById('building_display');
    const buildingIdHidden = document.getElementById('building_id_hidden');
    const selectedOption = locationSelect.options[locationSelect.selectedIndex];
    if (selectedOption.value) {
        const buildingName = selectedOption.getAttribute('data-building-name');
        const buildingId = selectedOption.getAttribute('data-building-id');
        buildingDisplay.textContent = buildingName || 'Tidak diketahui';
        buildingIdHidden.value = buildingId || '';
    } else {
        buildingDisplay.textContent = 'Pilih lokasi terlebih dahulu';
        buildingIdHidden.value = '';
    }
}

function toggleInfoBarang() {
    const section = document.getElementById('infoBarangSection');
    const icon = document.getElementById('toggleIcon');
    section.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('lokasi');
    if (locationSelect.value) {
        updateBuilding();
    }
});
</script>
@endpush
@endsection