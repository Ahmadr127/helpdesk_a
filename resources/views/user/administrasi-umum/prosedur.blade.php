@extends('user.layouts.app')

@section('title', 'Prosedur Administrasi Umum')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Prosedur Administrasi Umum</h1>

        <div class="space-y-6">
            <!-- Prosedur 1 -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                        <span class="font-bold">1</span>
                    </div>
                    <h3 class="text-lg font-semibold">Prosedur Pengajuan Barang</h3>
                </div>
                <div class="ml-11">
                    <ol class="list-decimal space-y-2">
                        <li>Unduh dan isi formulir pengajuan barang</li>
                        <li>Lengkapi dengan dokumen pendukung yang diperlukan</li>
                        <li>Ajukan ke kepala departemen untuk persetujuan</li>
                        <li>Serahkan ke bagian administrasi umum</li>
                        <li>Tunggu proses verifikasi dan persetujuan</li>
                    </ol>
                </div>
            </div>

            <!-- Prosedur 2 -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                        <span class="font-bold">2</span>
                    </div>
                    <h3 class="text-lg font-semibold">Prosedur Perbaikan Barang</h3>
                </div>
                <div class="ml-11">
                    <ol class="list-decimal space-y-2">
                        <li>Buat tiket perbaikan melalui sistem</li>
                        <li>Isi detail kerusakan dengan lengkap</li>
                        <li>Lampirkan foto jika diperlukan</li>
                        <li>Tunggu konfirmasi dari teknisi</li>
                        <li>Evaluasi hasil perbaikan</li>
                    </ol>
                </div>
            </div>

            <!-- Prosedur 3 -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                        <span class="font-bold">3</span>
                    </div>
                    <h3 class="text-lg font-semibold">Prosedur Peminjaman Fasilitas</h3>
                </div>
                <div class="ml-11">
                    <ol class="list-decimal space-y-2">
                        <li>Periksa ketersediaan fasilitas</li>
                        <li>Isi formulir peminjaman</li>
                        <li>Tentukan waktu penggunaan</li>
                        <li>Dapatkan persetujuan dari penanggung jawab</li>
                        <li>Konfirmasi peminjaman</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Catatan penting -->
        <div class="mt-8 bg-yellow-50 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Catatan Penting</h4>
                    <p class="text-yellow-600">
                        Pastikan untuk mengikuti setiap prosedur dengan benar dan lengkap.
                        Ketidaklengkapan dokumen atau prosedur dapat memperlambat proses persetujuan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 