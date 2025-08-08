{{-- filepath: resources/views/admin/users/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <form action="{{ route('users.store') }}" method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block">Nama</label>
                <input type="text" name="name" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" name="email" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block">Role</label>
                <select name="role" class="w-full border rounded px-2 py-1">
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block">Password</label>
                <input type="password" name="password" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-4">
                <label class="block">Products</label>
                @foreach($products as $product)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="products[{{ $product->id }}][checked]" value="1" class="mr-2">
                        <span class="w-40">{{ $product->name }}</span>
                        <span class="w-24">Rp{{ number_format($product->price,0,',','.') }}</span>
                        <input type="number" name="products[{{ $product->id }}][qty]" min="1" value="1" class="ml-2 w-16 border rounded px-1 py-0.5">
                    </div>
                @endforeach
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>
