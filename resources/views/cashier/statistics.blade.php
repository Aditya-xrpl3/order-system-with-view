{{-- filepath: resources/views/cashier/statistics.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistik Kasir') }}
        </h2>
    </x-slot>
    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-lg font-bold">Total Order</div>
                    <div class="text-2xl">{{ $totalOrders }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold">Order Selesai</div>
                    <div class="text-2xl text-green-600">{{ $completedOrders }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold">Pendapatan</div>
                    <div class="text-2xl text-blue-600">Rp{{ number_format($totalRevenue,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
