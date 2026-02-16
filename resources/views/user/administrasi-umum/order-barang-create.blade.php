@extends('user.layouts.app')

@section('title', 'Buat Order Perbaikan Baru')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Background with solid light blue and proper bottom padding -->
<div class="min-h-screen bg-gradient-to-r from-green-50 to-blue-50 pb-24">
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6 bg-white rounded-lg p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Buat Order Perbaikan Baru</h1>
                    <p class="mt-2 text-sm text-gray-600">Isi formulir di bawah untuk membuat order perbaikan</p>
                </div>
                <a href="{{ route('user.administrasi-umum.order-barang') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <form id="newOrderForm" class="p-6">
                @csrf
                <div class="grid grid-cols-12 gap-6">
                    <!-- Form Fields -->
                    <div class="col-span-12 space-y-4">
                        <!-- Nomor dan Tanggal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor</label>
                                <input type="text" name="nomor" id="nomor" readonly value="{{ $nomor }}"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                <div class="flex gap-2">
                                    <input type="text" id="tanggal_display" readonly
                                        class="w-2/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                    <input type="text" id="waktu_display" readonly
                                        class="w-1/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                </div>
                                <input type="hidden" name="tanggal" id="tanggal_input">
                            </div>
                        </div>

                        <!-- Unit Proses dan Penerima -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Proses</label>
                                <div class="flex gap-2">
                                    <input type="text" value="RTG" readonly
                                        class="w-1/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                    <input type="text" value="Instalasi Pemeliharaan (IPRS)" readonly
                                        class="w-2/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Penerima</label>
                                <div class="flex gap-2">
                                    <input type="text" value="MTC" readonly
                                        class="w-1/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                    <input type="text" value="Maintenance" readonly
                                        class="w-2/3 px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- NIP dan Prioritas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP Peminta <span class="text-red-500">*</span></label>
                                <input type="text" name="nip_peminta" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permintaan Pengerjaan <span class="text-red-500">*</span></label>
                                <select name="prioritas" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Prioritas</option>
                                    <option value="BIASA">BIASA</option>
                                    <option value="SEGERA">SEGERA</option>
                                    <option value="URGENT">URGENT</option>
                                </select>
                            </div>
                        </div>

                        <!-- Barang Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Barang <span class="text-red-500">*</span></label>
                                <select name="jenis_barang" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="Inventaris">Inventaris</option>
                                    <option value="Non-Inventaris">Non-Inventaris</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Inventaris <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_inventaris" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                            <select name="lokasi" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan <span class="text-red-500">*</span></label>
                            <textarea name="keluhan" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('user.administrasi-umum.order-barang') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set tanggal dan waktu saat halaman dimuat
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).split('/').join('-');
    const formattedTime = currentDate.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
    const isoDateTime = currentDate.toISOString().slice(0, 19).replace('T', ' ');

    document.getElementById('tanggal_display').value = formattedDate;
    document.getElementById('waktu_display').value = formattedTime;
    document.getElementById('tanggal_input').value = isoDateTime;
});

// Form submission handler
document.getElementById('newOrderForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    try {
        const formData = new FormData(this);
        const response = await fetch('{{ route("user.administrasi-umum.order-perbaikan.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Show success notification
            if (typeof createNotification === 'function') {
                createNotification(result.message, 'success');
            } else {
                alert(result.message);
            }
            
            // Redirect to index page after short delay
            setTimeout(function() {
                window.location.href = '{{ route("user.administrasi-umum.order-barang") }}';
            }, 1000);
        } else {
            throw new Error(result.message || 'Terjadi kesalahan saat membuat order');
        }
    } catch (error) {
        console.error('Error:', error);
        if (typeof createNotification === 'function') {
            createNotification(error.message || 'Terjadi kesalahan saat membuat order. Silakan coba lagi.', 'error');
        } else {
            alert(error.message || 'Terjadi kesalahan saat membuat order. Silakan coba lagi.');
        }
    }
});
</script>
@endsection