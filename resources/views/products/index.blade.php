{{-- filepath: resources/views/products/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($product->price,0,',','.') }}</td>
                        <td class="px-4 py-2">{{ $product->stock }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
