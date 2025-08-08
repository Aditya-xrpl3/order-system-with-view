{{-- filepath: resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mr-2">
                        Manajemen User
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Manajemen Produk
                    </a>
                </div>
                <div class="text-gray-900">
                    Selamat datang di Dashboard Admin!
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
