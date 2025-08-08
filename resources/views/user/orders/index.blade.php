{{-- filepath: resources/views/user/orders/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu & Riwayat Order Saya') }}
        </h2>
    </x-slot>
    <div class="py-6 max-w-5xl mx-auto">

        {{-- Menu Produk --}}
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-4">Menu</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded shadow p-4 flex flex-col items-center">
                        <img src="{{ $product->image_url ? asset('storage/'.$product->image_url) : 'https://via.placeholder.com/100' }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded mb-2">
                        <div class="font-semibold">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500 mb-2">Rp{{ number_format($product->price,0,',','.') }}</div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mt-2">
                                Pesan
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Riwayat Order --}}
        <h3 class="font-bold text-lg mb-4 mt-8">Riwayat Order</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Meja</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $order->table->number ?? '-' }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $order->status == 'completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">Detail</a>
                            <a href="{{ route('order.receipt', $order) }}" class="text-green-600 hover:underline">Download Struk</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Belum ada order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
