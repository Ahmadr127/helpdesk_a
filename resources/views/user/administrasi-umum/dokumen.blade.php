@extends('user.layouts.app')

@section('title', 'Dokumen Administrasi Umum')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Dokumen Administrasi Umum</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Placeholder untuk dokumen -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold">Dokumen 1</h3>
                </div>
                <p class="text-gray-600 mb-3">Deskripsi dokumen akan ditampilkan di sini.</p>
                <a href="#" class="text-blue-500 hover:text-blue-700 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Unduh
                </a>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-file-word text-blue-500 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold">Dokumen 2</h3>
                </div>
                <p class="text-gray-600 mb-3">Deskripsi dokumen akan ditampilkan di sini.</p>
                <a href="#" class="text-blue-500 hover:text-blue-700 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Unduh
                </a>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-file-excel text-green-500 text-2xl mr-3"></i>
                    <h3 class="text-lg font-semibold">Dokumen 3</h3>
                </div>
                <p class="text-gray-600 mb-3">Deskripsi dokumen akan ditampilkan di sini.</p>
                <a href="#" class="text-blue-500 hover:text-blue-700 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Unduh
                </a>
            </div>
        </div>

        <!-- Catatan informasi -->
        <div class="mt-8 bg-blue-50 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-blue-800">Informasi</h4>
                    <p class="text-blue-600">
                        Halaman ini berisi dokumen-dokumen yang berkaitan dengan administrasi umum.
                        Silakan hubungi administrator jika Anda membutuhkan dokumen tambahan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 