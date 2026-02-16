@forelse($orders as $order)
<tr class="hover:bg-gray-50 transition-colors duration-200">
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm font-medium text-gray-900">{{ $order->nomor }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600">{{ $order->tanggal->format('d/m/Y') }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-900">{{ $order->nama_barang }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
               ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 
               ($order->status === 'completed' ? 'bg-green-100 text-green-700 border border-green-200' : 
               'bg-red-100 text-red-700 border border-red-200')) }}">
            <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                {{ $order->status === 'pending' ? 'bg-yellow-400' : 
                   ($order->status === 'in_progress' ? 'bg-blue-400' : 
                   ($order->status === 'completed' ? 'bg-green-400' : 
                   'bg-red-400')) }}">
            </span>
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
            {{ $order->prioritas === 'URGENT' ? 'bg-red-100 text-red-700 border border-red-200' : 
               ($order->prioritas === 'SEGERA' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
               'bg-green-100 text-green-700 border border-green-200') }}">
            <svg class="w-3 h-3 mr-1.5
                {{ $order->prioritas === 'URGENT' ? 'text-red-500' : 
                   ($order->prioritas === 'SEGERA' ? 'text-yellow-500' : 
                   'text-green-500') }}" 
                fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $order->prioritas }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <button onclick="showOrderDetail('{{ $order->id }}')" 
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Detail
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-12 text-center">
        <div class="flex flex-col items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada order perbaikan</h3>
            <p class="mt-1 text-sm text-gray-500">Belum ada order perbaikan yang dibuat saat ini.</p>
        </div>
    </td>
</tr>
@endforelse