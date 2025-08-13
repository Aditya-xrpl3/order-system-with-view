{{-- filepath: resources/views/receipts/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Struk') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Order</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $receipt->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2">#{{ $receipt->order_id }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($receipt->total,0,',','.') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('receipts.show', $receipt) }}" class="text-blue-600 hover:underline">Lihat</a>
                            <a href="{{ route('receipts.download', $receipt) }}" class="text-green-600 hover:underline ml-2">Download PDF</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Belum ada struk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
