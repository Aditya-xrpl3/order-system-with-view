{{-- filepath: resources/views/admin/products/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Tambah Produk
        </a>
        <table class="min-w-full mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Harga</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Foto</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="border px-4 py-2">{{ $product->name }}</td>
                    <td class="border px-4 py-2">{{ $product->price }}</td>
                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                    <td class="border px-4 py-2">
                        @if($product->image_url)
                            <img src="{{ asset('storage/'.$product->image_url) }}" alt="Foto" class="w-12 h-12 object-cover rounded">
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600">Edit</a>
                        <form action="#" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Yakin hapus produk?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
