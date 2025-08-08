{{-- filepath: resources/views/user/orders/menu.blade.php --}}
<x-app-layout>
    <div class="flex bg-gray-100 min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-20 bg-black flex flex-col items-center py-6 space-y-6">
            <a href="#" class="text-white text-2xl font-bold mb-8">S.</a>
            <a href="#" class="text-white"><i class="fas fa-home"></i></a>
            <a href="#" class="text-white bg-yellow-200 p-2 rounded"><i class="fas fa-utensils"></i></a>
            <a href="#" class="text-white"><i class="fas fa-shopping-cart"></i></a>
            <a href="#" class="text-white"><i class="fas fa-map-marker-alt"></i></a>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col md:flex-row gap-4 p-6">
            {{-- Menu Section --}}
            <section class="flex-1 bg-white rounded shadow p-4">
                <h2 class="font-bold text-lg mb-4">SUSHI FOOD</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($products as $product)
                        <div class="bg-gray-50 rounded shadow p-2 flex flex-col items-center">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded mb-2">
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-sm text-gray-500 mb-2">Rp{{ number_format($product->price,0,',','.') }}</div>
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-black text-white px-3 py-1 rounded text-xs">Add to Cart</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Cart Section --}}
            <section class="w-full md:w-80 bg-white rounded shadow p-4">
                <h2 class="font-bold text-lg mb-4">CART</h2>
                @forelse($cartItems as $item)
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/40' }}" class="w-10 h-10 rounded" alt="">
                            <span>{{ $item->product->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-12 border rounded px-1 py-0.5 text-center">
                                <button type="submit" class="ml-1 text-xs bg-gray-200 px-2 py-0.5 rounded">Update</button>
                            </form>
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-lg">&times;</button>
                            </form>
                        </div>
                        <span class="ml-2">Rp{{ number_format($item->product->price * $item->quantity,0,',','.') }}</span>
                    </div>
                @empty
                    <div class="text-gray-500">Cart kosong.</div>
                @endforelse

                {{-- Subtotal & Confirm --}}
                <div class="mt-4 border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Subtotal</span>
                        <span>Rp{{ number_format($cartTotal,0,',','.') }}</span>
                    </div>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="table_id" class="block text-sm mb-1">Table No</label>
                            <select name="table_id" id="table_id" class="w-full border rounded px-2 py-1" required>
                                <option value="">Pilih Meja</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ in_array($table->id, $usedTableIds ?? []) ? 'disabled' : '' }}>
                                        {{ $table->number }}{{ in_array($table->id, $usedTableIds ?? []) ? ' (Sedang dipakai)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-black text-white py-2 rounded">Confirm Order</button>
                    </form>
                </div>
            </section>

            {{-- Receipt Section (Optional, tampilkan setelah order dibuat) --}}
            @isset($order)
            <section class="w-full md:w-80 bg-white rounded shadow p-4">
                <h2 class="font-bold text-lg mb-4">Your Receipt</h2>
                @foreach($order->items as $item)
                    <div class="flex justify-between mb-1">
                        <span>{{ $item->product->name }}</span>
                        <span>Rp{{ number_format($item->price * $item->quantity,0,',','.') }}</span>
                    </div>
                @endforeach
                <div class="border-t mt-2 pt-2 flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp{{ number_format($order->total_price,0,',','.') }}</span>
                </div>
                <div class="mt-2">
                    <span class="font-semibold">Table No : </span>
                    <span>{{ $order->table->number ?? '-' }}</span>
                </div>
            </section>
            @endisset
        </main>
    </div>
</x-app-layout>
