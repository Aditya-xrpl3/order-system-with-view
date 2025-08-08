{{-- filepath: resources/views/admin/products/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Produk
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Harga</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Stok</label>
                        <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Foto Produk</label>
                        @if($product->image_url)
                            <img src="{{ asset('storage/'.$product->image_url) }}" alt="Foto Produk" class="mb-2 w-24 h-24 object-cover rounded">
                        @endif
                        <input type="file" name="image" accept="image/*" class="w-full border rounded px-2 py-1">
                        <small class="text-gray-500">Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Update
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
