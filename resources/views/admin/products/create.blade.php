{{-- filepath: resources/views/admin/products/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Produk
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1">Nama Produk</label>
                        <input type="text" name="name" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Harga</label>
                        <input type="number" name="price" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Stok</label>
                        <input type="number" name="stock" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Foto Produk</label>
                        <input type="file" name="image" accept="image/*" class="w-full border rounded px-2 py-1">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
