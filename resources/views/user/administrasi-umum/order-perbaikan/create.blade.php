@extends('user.layouts.app')

@section('title', 'Buat Order Perbaikan Baru')

@section('content')
@include('user.partials.notification')

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">

        @if(!auth()->user()->department)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Anda belum memiliki data departemen. Silakan lengkapi profil Anda di
                        <a href="{{ route('user.settings') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">pengaturan profil</a>.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-blue-300 px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Buat Order Perbaikan Baru</h2>
                <a href="{{ route('user.administrasi-umum.order-barang') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 bg-opacity-80 rounded-lg text-white hover:bg-opacity-30 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('user.administrasi-umum.order-perbaikan.store') }}"
                    method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d H:i:s') }}">

                    <!-- Keluhan / Deskripsi -->
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

                    <!-- Row: Kategori & Departemen -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('category_id') border-red-400 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                            <div class="flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="flex-1">
                                    <p class="text-gray-900">{{ $userDepartment?->name ?? '-' }}</p>
                                    @if($userDepartment)
                                    <p class="text-xs text-gray-500">Kode: {{ $userDepartment->code }}</p>
                                    @endif
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="hidden" name="department_id" value="{{ $userDepartment?->id }}">
                        </div>
                    </div>

                    <!-- Row: Lokasi & Gedung -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <select name="lokasi" id="lokasi"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('lokasi') border-red-400 @enderror"
                                required onchange="updateBuilding()">
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}"
                                    data-building-id="{{ $location->building?->id }}"
                                    data-building-name="{{ $location->building?->name }}"
                                    {{ old('lokasi') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gedung</label>
                            <div class="flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="flex-1">
                                    <p id="building_display" class="text-gray-900">Pilih lokasi terlebih dahulu</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input type="hidden" name="building_id" id="building_id_hidden">
                        </div>
                    </div>

                    <!-- Row: Prioritas & Foto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                            <select name="prioritas" id="prioritas"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('prioritas') border-red-400 @enderror"
                                required>
                                <option value="">Pilih Prioritas</option>
                                <option value="RENDAH" {{ old('prioritas') === 'RENDAH' ? 'selected' : '' }}>Rendah</option>
                                <option value="SEDANG" {{ old('prioritas') === 'SEDANG' ? 'selected' : '' }}>Sedang</option>
                                <option value="TINGGI/URGENT" {{ old('prioritas') === 'TINGGI/URGENT' ? 'selected' : '' }}>Tinggi / Urgent</option>
                            </select>
                            @error('prioritas')
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
                            @error('foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('user.administrasi-umum.order-barang') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            Buat Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('lokasi');
    if (locationSelect.value) {
        updateBuilding();
    }

    // Show selected file name
    const fotoInput = document.getElementById('foto');
    fotoInput.addEventListener('change', function() {
        const filenameSpan = document.getElementById('foto_filename');
        const fotoName = document.getElementById('foto-name');
        if (this.files && this.files[0]) {
            filenameSpan.textContent = this.files[0].name;
            fotoName.textContent = this.files[0].name + ' (' + (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
        } else {
            filenameSpan.textContent = 'Pilih Foto';
            fotoName.textContent = 'Ukuran file maksimum: 5MB';
        }
    });
});
</script>
@endpush
@endsection
