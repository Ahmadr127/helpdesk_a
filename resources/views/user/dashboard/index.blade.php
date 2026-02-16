@extends('user.layouts.app')

@section('title', 'Dasbor')

@section('content')
<!-- Include the notification partial -->
@include('user.partials.notification')

<div class="container mx-auto px-4 py-8 max-w-8xl">
    <h1 class="text-2xl font-semibold text-gray-800 mb-8">Selamat Datang di Helpdesk RS Azra</h1>

    <!-- Ticket Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 mb-10">
        <!-- Total Tickets Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <a href="{{ route('user.ticket.filter.status', 'all') }}" class="block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-blue-50 to-blue-100 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Tiket</p>
                        <h3 class="text-2xl font-bold">{{ $totalTickets }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Open Tickets Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <a href="{{ route('user.ticket.filter.status', 'open') }}" class="block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-blue-50 to-blue-100 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tiket Dibuka</p>
                        <h3 class="text-2xl font-bold">{{ $openTickets }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- In Progress Tickets Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <a href="{{ route('user.ticket.filter.status', 'in_progress') }}" class="block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-yellow-50 to-yellow-100 mr-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tiket Dalam Proses</p>
                        <h3 class="text-2xl font-bold">{{ $inProgressTickets }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Closed Tickets Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <a href="{{ route('user.ticket.filter.status', 'closed') }}" class="block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-purple-50 to-purple-100 mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Menunggu Konfirmasi</p>
                        <h3 class="text-2xl font-bold">{{ $closedTickets }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Confirmed Tickets Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <a href="{{ route('user.ticket.filter.status', 'confirmed') }}" class="block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-green-50 to-green-100 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Dikonfirmasi</p>
                        <h3 class="text-2xl font-bold">{{ $confirmedTickets }}</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Riwayat Tiket Anda</h2>
            <a href="{{ route('user.ticket.create') }}"
                class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition-colors shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Tiket Baru
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="card-header px-6 py-4">
                <h2 class="text-base font-semibold text-gray-800">Aktivitas Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No Tiket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subjek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Departemen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->ticket_number }}</td>
                            <td class="px-6 py-4">{{ Str::limit($ticket->description, 30) }}</td>
                            <td class="px-6 py-4">{{ $ticket->department }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs leading-5 font-semibold rounded-full 
                                {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : 
                                   ($ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($ticket->status === 'closed' ? 'bg-purple-100 text-purple-800' : 
                                   ($ticket->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   'bg-gray-100 text-gray-800'))) }}">
                                    {{ $ticket->status === 'closed' ? 'Menunggu Konfirmasi' : ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('user.ticket.show', $ticket) }}"
                                    class="text-blue-600 hover:text-blue-900 font-medium">Lihat Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Belum ada tiket yang dibuat
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('user.ticket.index') }}"
                class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                Lihat Semua Tiket
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Review Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <!-- User Reviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            style="position: relative; z-index: 1;">
            <div class="card-header px-6 py-4">
                <h3 class="text-base font-semibold text-gray-800">Ulasan Pengguna</h3>
            </div>
            <div class="p-6">
                <div class="fixed-height-container max-h-80 overflow-y-auto pr-2">
                    @foreach($userFeedback as $feedback)
                    <div class="border-b border-gray-100 pb-4 mb-4 last:border-b-0 last:mb-0 last:pb-0">
                        <div class="flex items-center mb-2">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-green-50 to-blue-50 flex items-center justify-center text-green-600 font-medium">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++) <span
                                            class="{{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                            ★</span>
                                            @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 ml-2">{{ $feedback->rating }}.0/5.0</span>
                                </div>
                            </div>
                            <div class="ml-auto text-xs text-gray-400">{{ $feedback->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-800">{{ $feedback->subject }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $feedback->message }}</p>

                        @if($feedback->admin_reply)
                        <div class="mt-3 pl-4 border-l-2 border-green-500">
                            <p class="text-sm font-medium text-green-600">Balasan Admin:</p>
                            <p class="text-sm text-gray-600">{{ $feedback->admin_reply }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $feedback->replied_at->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @if(count($userFeedback) === 0)
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        Belum ada ulasan
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="card-header px-6 py-4">
                <h3 class="text-base font-semibold text-gray-800">Berikan Umpan Balik Anda</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('user.feedback.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Penilaian</label>
                        <div class="flex items-center space-x-2">
                            <div class="star-rating flex flex-row-reverse">
                                @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer"
                                    required>
                                <label for="star{{ $i }}" class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-400
                                        {{ $i > 1 ? 'peer-checked/star'.$i.':text-yellow-400' : '' }}">
                                    ★
                                </label>
                                @endfor
                            </div>
                        </div>
                        @error('rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select id="category" name="category"
                                class="appearance-none w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-green-100 focus:border-green-400 transition-colors pr-10"
                                required>
                                <option value="">Pilih Kategori</option>
                                <option value="Medical Service">Layanan Medis</option>
                                <option value="Facilities">Fasilitas</option>
                                <option value="Administration">Administrasi</option>
                                <option value="IT Support">Dukungan IT</option>
                                <option value="Other">Lainnya</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                        <input type="text" id="subject" name="subject"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-100 focus:border-green-400 transition-colors"
                            required>
                        @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea id="message" name="message" rows="4"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-green-100 focus:border-green-400 transition-colors"
                            required></textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                            Kirim Umpan Balik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
.fixed-height-container {
    max-height: 400px;
}

/* Star rating */
.star-rating input[type="radio"]:checked~label {
    color: #facc15;
}

.star-rating label:hover,
.star-rating label:hover~label {
    color: #facc15;
}
</style>
@endsection

@endsection