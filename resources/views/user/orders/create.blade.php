{{-- filepath: resources/views/user/orders/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang & Checkout') }}
        </h2>
    </x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf

            <!-- Pilih Meja -->
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Pilih Meja</label>
                <select name="table_id" class="w-full border rounded px-2 py-1" required>
                    <option value="">-- Pilih Meja --</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ in_array($table->id, $usedTableIds ?? []) ? 'disabled' : '' }}>
                            {{ $table->number }}{{ in_array($table->id, $usedTableIds ?? []) ? ' (Sedang dipakai)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Keranjang Produk -->
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Keranjang Produk</label>
                @forelse($products as $product)
                    <div class="flex items-center mb-2 border-b pb-2">
                        <input type="checkbox" name="items[{{ $product->id }}][checked]" value="1" class="mr-2">
                        <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                        <span class="w-40">{{ $product->name }}</span>
                        <span class="w-24">Rp{{ number_format($product->price,0,',','.') }}</span>
                        <input type="number" name="items[{{ $product->id }}][quantity]" value="1" min="1" class="ml-2 w-16 border rounded px-1 py-0.5">
                    </div>
                @empty
                    <div class="text-gray-500">Belum ada produk.</div>
                @endforelse
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded shadow transition">
                Buat Order
            </button>
        </form>
    </div>
</x-app-layout>
