@extends('user.layouts.app')

@section('title', 'Administrasi Umum')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Administrasi Umum</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Placeholder for content -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Dokumen Umum</h2>
                <p class="text-gray-600">Akses dan kelola dokumen administrasi umum.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Formulir</h2>
                <p class="text-gray-600">Akses berbagai formulir administrasi.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Prosedur</h2>
                <p class="text-gray-600">Informasi prosedur dan kebijakan administrasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection