@extends('user.layouts.app')

@section('title', $status === 'all' ? 'Semua Tiket' : ucfirst($status) . ' Tiket')

@section('content')
@include('user.partials.notification')

<div class="container mx-auto px-4 py-8">
    <!-- Filter Section -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <h2 class="text-xl font-semibold mb-4">{{ $status === 'all' ? 'Semua Tiket' : ucfirst($status) . ' Tiket' }}
        </h2>

        <form action="{{ route('user.ticket.filter.status', $status) }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Time Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode Waktu</label>
                    <select name="time_filter" class="w-full rounded-md border-gray-300"
                        onchange="toggleMonthFilter(this.value)">
                        <option value="all" {{ $timeFilter == 'all' ? 'selected' : '' }}>Sepanjang Waktu</option>
                        <option value="today" {{ $timeFilter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $timeFilter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $timeFilter == 'month' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <!-- Month Filter -->
                <div id="monthFilter" class="{{ $timeFilter != 'month' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan</label>
                    <select name="month" class="w-full rounded-md border-gray-300">
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Status Filter Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('user.ticket.filter.status', 'all') }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $status === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua Tiket
                </a>
                <a href="{{ route('user.ticket.filter.status', 'open') }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $status === 'open' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dibuka
                </a>
                <a href="{{ route('user.ticket.filter.status', 'in_progress') }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $status === 'in_progress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dalam Proses
                </a>
                <a href="{{ route('user.ticket.filter.status', 'closed') }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $status === 'closed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Ditutup (Menunggu Konfirmasi)
                </a>
                <a href="{{ route('user.ticket.filter.status', 'confirmed') }}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $status === 'confirmed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dikonfirmasi
                </a>
            </nav>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Tiket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gedung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat Pada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                <tr>
                    <td class="px-6 py-4">{{ $ticket->ticket_number }}</td>
                    <td class="px-6 py-4">{{ ucfirst($ticket->category) }}</td>
                    <td class="px-6 py-4">{{ $ticket->department }}</td>
                    <td class="px-6 py-4">{{ $ticket->building }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : 
                               ($ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                               ($ticket->status === 'closed' ? 'bg-purple-100 text-purple-800' : 
                               ($ticket->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                               'bg-gray-100 text-gray-800'))) }}">
                            {{ $ticket->status === 'closed' ? 'Ditutup (Menunggu Konfirmasi)' : ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $ticket->priority === 'low' ? 'bg-gray-100 text-gray-800' : 
                               ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-red-100 text-red-800') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('user.ticket.show', $ticket) }}"
                                class="text-blue-600 hover:text-blue-900">Lihat</a>

                            @if($ticket->status === 'open')
                            <a href="{{ route('user.ticket.edit', $ticket) }}"
                                class="text-blue-600 hover:text-blue-900">Edit</a>

                            <form action="{{ route('user.ticket.destroy', $ticket) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini?');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada tiket ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function toggleMonthFilter(value) {
    const monthFilter = document.getElementById('monthFilter');
    monthFilter.classList.toggle('hidden', value !== 'month');
}
</script>
@endpush
@endsection