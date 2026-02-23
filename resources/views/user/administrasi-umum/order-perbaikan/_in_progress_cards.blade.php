@forelse($inProgressOrders ?? [] as $order)
<div class="flex-none w-80">
    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
        <div class="p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <span class="text-sm font-medium text-gray-900">{{ $order->nomor }}</span>
                    <p class="text-xs text-gray-500">{{ $order->tanggal->format('d/m/Y H:i') }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                    In Progress
                </span>
            </div>
            <div class="mb-3">
                <h3 class="text-sm font-medium text-gray-900">{{ $order->category?->name ?? '-' }}</h3>
                <p class="text-xs text-gray-500 mb-1">{{ $order->department?->name ?? '-' }}</p>
                <p class="text-xs text-gray-600 line-clamp-2">{{ $order->keluhan }}</p>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    {{ match($order->prioritas) {
                        'RENDAH' => 'bg-green-100 text-green-700',
                        'SEDANG' => 'bg-yellow-100 text-yellow-700',
                        'TINGGI/URGENT' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-600'
                    } }}">
                    {{ $order->prioritas }}
                </span>
                <button onclick="showOrderDetail('{{ $order->id }}')"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Detail →
                </button>
            </div>
        </div>
    </div>
</div>
@empty
<div class="w-full bg-gray-50 rounded-lg p-6 text-center">
    <p class="text-gray-500">Tidak ada order dalam proses</p>
</div>
@endforelse