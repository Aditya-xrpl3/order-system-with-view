{{-- filepath: resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 px-3 py-1 rounded-full text-purple-800 text-sm font-medium">
                    {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Card --}}
            <div class="bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 rounded-2xl p-8 text-white mb-8 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Selamat datang di Dashboard Admin!</h3>
                        <p class="text-purple-100 text-lg">Kelola sistem restoran Anda dengan mudah</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"/>
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Users</p>
                            <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                            <p class="text-green-600 text-sm mt-1">+2 user baru</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Produk</p>
                            <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Product::count() }}</p>
                            <p class="text-blue-600 text-sm mt-1">{{ \App\Models\Product::where('stock', '>', 0)->count() }} tersedia</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Pesanan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Order::count() }}</p>
                            <p class="text-yellow-600 text-sm mt-1">{{ \App\Models\Order::where('status', 'pending')->count() }} pending</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Revenue Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900">Rp{{ number_format(\App\Models\Order::whereDate('created_at', today())->where('status', 'completed')->sum('total_price'), 0, ',', '.') }}</p>
                            <p class="text-green-600 text-sm mt-1">+15% dari kemarin</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                        </svg>
                        Aksi Cepat
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('users.create') }}"
                           class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-blue-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                                <span class="text-sm font-medium text-blue-700">Tambah User</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.products.create') }}"
                           class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-green-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                </svg>
                                <span class="text-sm font-medium text-green-700">Tambah Produk</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        Produk Stok Rendah
                    </h3>
                    <div class="space-y-3 max-h-48 overflow-y-auto">
                        @foreach(\App\Models\Product::where('stock', '<=', 5)->take(5)->get() as $product)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-yellow-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">Stok: {{ $product->stock }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">
                                Edit
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Management Links --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('users.index') }}"
                   class="block bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold mb-2">Manajemen User</h3>
                            <p class="text-blue-100">Kelola pengguna sistem</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="block bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold mb-2">Manajemen Produk</h3>
                            <p class="text-green-100">Kelola menu dan produk</p>
                        </div>
                        <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
