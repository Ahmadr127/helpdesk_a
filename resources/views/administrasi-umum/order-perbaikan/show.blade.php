@extends('administrasi-umum.layouts.app')

@section('title', 'Detail Order Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header with back button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Order Perbaikan</h1>
            <a href="{{ route('administrasi-umum.order-perbaikan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Informasi Order</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nomor Order</p>
                        <p class="font-medium">{{ $orderPerbaikan->nomor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="font-medium">{{ $orderPerbaikan->tanggal->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span
                            class="inline-flex px-2 py-1 text-xs rounded-full {{ $orderPerbaikan->getStatusBadgeClass() }}">
                            {{ $orderPerbaikan->getStatusText() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Prioritas</p>
                        <span class="inline-flex px-2 py-1 text-xs rounded-full 
                            {{ $orderPerbaikan->prioritas === 'URGENT' ? 'bg-red-100 text-red-800' : 
                               ($orderPerbaikan->prioritas === 'SEGERA' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-green-100 text-green-800') }}">
                            {{ $orderPerbaikan->prioritas }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Informasi Peminta</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">NIP Peminta</p>
                        <p class="font-medium">{{ $orderPerbaikan->nip_peminta }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Peminta</p>
                        <p class="font-medium">{{ $orderPerbaikan->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Departemen</p>
                        <p class="font-medium">{{ $orderPerbaikan->creator->department->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Information -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-4">Informasi Barang</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Jenis Barang</p>
                    <p class="font-medium">{{ $orderPerbaikan->jenis_barang }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kode Inventaris</p>
                    <p class="font-medium">{{ $orderPerbaikan->kode_inventaris }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Barang</p>
                    <p class="font-medium">{{ $orderPerbaikan->nama_barang }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Lokasi</p>
                    <p class="font-medium">{{ $orderPerbaikan->location->name }}</p>
                </div>
            </div>
        </div>

        <!-- Keluhan -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-4">Keluhan</h3>
            <p class="whitespace-pre-wrap">{{ $orderPerbaikan->keluhan }}</p>
        </div>

        <!-- Tindak Lanjut dan Penanggung Jawab -->
        @if($orderPerbaikan->status === 'konfirmasi' || $orderPerbaikan->status === 'reject')
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-4">Informasi Tindak Lanjut</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Penanggung Jawab</p>
                    <p class="font-medium">{{ $orderPerbaikan->nama_penanggung_jawab ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Terakhir Diperbarui</p>
                    <p class="font-medium">{{ $orderPerbaikan->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Tindak Lanjut</p>
                    <div class="mt-1 p-3 bg-white border border-gray-200 rounded-md">
                        <p class="whitespace-pre-wrap">{{ $orderPerbaikan->follow_up ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Response Form -->
        @if($orderPerbaikan->status === 'pending')
        <form id="responseForm" class="bg-white p-6 rounded-lg shadow-sm border mb-6">
            <h3 class="text-lg font-semibold mb-4">Respon Order</h3>
            <div class="space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="konfirmasi">Konfirmasi</option>
                        <option value="reject">Tolak</option>
                    </select>
                </div>

                <div>
                    <label for="nama_penanggung_jawab" class="block text-sm font-medium text-gray-700">
                        Nama Penanggung Jawab
                    </label>
                    <input type="text" name="nama_penanggung_jawab" id="nama_penanggung_jawab" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="follow_up" class="block text-sm font-medium text-gray-700">Tindak Lanjut</label>
                    <textarea name="follow_up" id="follow_up" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukkan tindak lanjut atau keterangan respon..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Kirim Respon
                    </button>
                </div>
            </div>
        </form>
        @endif

        <!-- History -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Riwayat Order</h3>
            <div class="space-y-4">
                @foreach($orderPerbaikan->history()->latest()->get() as $history)
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
                            <p class="mt-2 text-gray-700">{{ $history->follow_up }}</p>
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

@push('scripts')
<script>
document.getElementById('responseForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    try {
        const formData = new FormData(this);
        const response = await fetch(
            `/administrasi-umum/order-perbaikan/{{ $orderPerbaikan->id }}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan saat memperbarui status');
        }

        // Show success message and redirect
        alert(result.message);
        window.location.href = '{{ route("administrasi-umum.order-perbaikan.index") }}';

    } catch (error) {
        console.error('Error:', error);
        alert(error.message);
    }
});
</script>
@endpush
@endsection