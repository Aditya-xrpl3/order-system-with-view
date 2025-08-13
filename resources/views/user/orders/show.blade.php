{{-- filepath: resources/views/user/orders/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Order') }}
        </h2>
    </x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}<br>
                <strong>No Meja:</strong> {{ $order->table->number ?? '-' }}<br>
                <strong>Status:</strong>
                <span class="px-2 py-1 rounded text-xs {{ $order->status == 'completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="mb-4">
                <strong>Item Pesanan:</strong>
                <ul class="list-disc ml-6">
                    @foreach($order->orderItems as $item)
                        <li>
                            {{ $item->product->name }} x {{ $item->quantity }}
                            (Rp{{ number_format($item->price * $item->quantity,0,',','.') }})
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mb-4">
                <strong>Total:</strong> Rp{{ number_format($order->total_price,0,',','.') }}
            </div>
            <a href="{{ route('order.receipt', $order) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Download Struk</a>
        </div>
    </div>
</x-app-layout>
