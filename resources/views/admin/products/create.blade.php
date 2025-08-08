{{-- filepath: resources/views/admin/products/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk Baru') }}
            </h2>
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Form Tambah Produk</h3>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    {{-- Nama Produk --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   value="{{ old('price') }}"
                                   min="0"
                                   step="0.01"
                                   required
                                   class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="mb-6">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input type="number"
                               name="stock"
                               id="stock"
                               value="{{ old('stock') }}"
                               min="0"
                               required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('image') border-red-500 @enderror">
                        <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
