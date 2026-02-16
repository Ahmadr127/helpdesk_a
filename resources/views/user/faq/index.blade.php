@extends('user.layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">FAQ (Frequently Asked Questions)</h1>

        <h2 class="text-xl text-green-500 text-center mb-8">How can we help you ?</h2>

        <div class="space-y-4">
            <!-- FAQ Items -->
            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Jenis Asuransi Kesehatan apa yang diterima di RS AZRA ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Apakah RS AZRA melayani pengobatan dengan jaminan BPJS Kesehatan
                        ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Dimanakah pasien bisa mendapatkan informasi mengenai pelayanan
                        pendaftaran ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Jenis Kamar apa saja yang tersedia di RS AZRA</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Fasilitas apa yang disediakan di dalam kamar rawat inap ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Pada jam berapa pengunjung boleh mengunjungi pasien rawat inap ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow">
                <button class="w-full p-4 text-left flex items-center justify-between">
                    <span class="text-gray-700">Belum Mendapatkan Informasi yang Anda Inginkan ?</span>
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection