{{-- filepath: resources/views/user/orders/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Menu & Riwayat Order Saya') }}
            </h2>
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
                <span>Selamat datang di restoran kami</span>
            </div>
        </div>
    </x-slot>

    @php
        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
    @endphp

    @if($cartCount > 0)
    <div class="fixed bottom-6 right-6 z-10">
        <a href="{{ route('cart.index') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors">
            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Lihat Keranjang ({{ $cartCount }})
        </a>
    </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Tampilkan menu di sini --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg group">
                    <div class="h-48 overflow-hidden">
                        <img
                            src="{{ $product->image_url ? asset('storage/'.$product->image_url) : 'https://via.placeholder.com/300x200/f3f4f6/9ca3af?text=No+Image' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-xl text-gray-900">{{ $product->name }}</h3>
                            <span class="text-sm text-green-500 bg-green-100 px-2 py-1 rounded">Tersedia</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-bold text-lg">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-gray-600 text-sm">Stok: {{ $product->stock }}</span>
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
