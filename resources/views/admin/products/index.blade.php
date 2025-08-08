{{-- filepath: resources/views/admin/products/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"/>
                </svg>
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->count() }}</p>
                            <p class="text-sm text-gray-600">Total Produk</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->where('stock', '>', 0)->count() }}</p>
                            <p class="text-sm text-gray-600">Tersedia</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->where('stock', '<=', 0)->count() }}</p>
                            <p class="text-sm text-gray-600">Stok Habis</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->where('stock', '<=', 5)->where('stock', '>', 0)->count() }}</p>
                            <p class="text-sm text-gray-600">Stok Rendah</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                        {{-- Product Image --}}
                        <div class="relative h-48 bg-gray-100">
                            <img src="{{ $product->image_url ? asset('storage/'.$product->image_url) : 'https://via.placeholder.com/300x200/f3f4f6/9ca3af?text=No+Image' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">

                            {{-- Stock Badge --}}
                            @if($product->stock > 5)
                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Tersedia
                                </div>
                            @elseif($product->stock > 0)
                                <div class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Stok Rendah
                                </div>
                            @else
                                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Habis
                                </div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 text-lg mb-2">{{ $product->name }}</h4>

                            <div class="flex items-center justify-between mb-3">
                                <div class="text-xl font-bold text-blue-600">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Stok: {{ $product->stock }}
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors text-center text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                            class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-3 rounded-lg transition-colors text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($products->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada produk</p>
                    <p class="text-gray-400 text-sm mb-4">Tambahkan produk pertama Anda</p>
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                        </svg>
                        Tambah Produk
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
