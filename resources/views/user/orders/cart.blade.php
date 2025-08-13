{{-- filepath: resources/views/user/orders/cart.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Anda') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Cart Items -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Item Pesanan</h3>

                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($cartItems as $item)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-16 rounded-md overflow-hidden bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                                            <img
                                                src="{{ $item->product->image_url ? asset('storage/'.$item->product->image_url) : 'https://via.placeholder.com/150?text=' . urlencode($item->product->name) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover"
                                                onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text={{ urlencode($item->product->name) }}';"
                                            >
                                        </div>
                                        <div>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md">
                                            <form action="{{ route('cart.decrease', $item->product_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                                    -
                                                </button>
                                            </form>

                                            <span class="px-3 py-1 text-gray-800 dark:text-gray-200">{{ $item->quantity }}</span>

                                            <form action="{{ route('cart.increase', $item->product_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                                    +
                                                </button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <p class="font-medium text-gray-900 dark:text-white">Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>

                                        <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pesanan</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="font-medium text-gray-900 dark:text-white">Rp{{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="border-t pt-3 border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between font-medium">
                                        <span class="text-gray-900 dark:text-white">Total</span>
                                        <span class="text-blue-600 dark:text-blue-400 text-lg">Rp{{ number_format($cartTotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="table" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih Meja
                                </label>

                                <form action="{{ route('orders.store') }}" method="POST">
                                    @csrf
                                    <select name="table_id" id="table" class="w-full mb-4 rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
                                        @if($tables->isEmpty())
                                            <option value="">-- Tidak ada meja tersedia --</option>
                                        @else
                                            @foreach($tables as $table)
                                                <option value="{{ $table->id }}">Meja {{ $table->number }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Checkout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('menu') }}" class="flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Keranjang Anda kosong</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Sepertinya Anda belum menambahkan apapun ke keranjang.</p>
                    <div class="mt-6">
                        <a href="{{ route('menu') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 transition">
                            Lihat Menu
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
