{{-- filepath: resources/views/user/orders/cart.blade.php --}}
<x-app-layout>
  <section>
    <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
      <div class="mx-auto max-w-3xl">
        <header class="text-center">
          <h1 class="text-xl font-bold text-white sm:text-3xl">Keranjang Anda</h1>
        </header>

        <div class="mt-8">
          <ul class="space-y-4">
            @forelse($cartItems as $item)
            <li class="flex items-center gap-4">
              <img
                src="{{ $item->product->image_url ?? 'https://via.placeholder.com/100' }}"
                alt="{{ $item->product->name }}"
                class="size-16 rounded-sm object-cover"
              />

              <div>
                <h3 class="text-sm text-white">{{ $item->product->name }}</h3>
                <dl class="mt-0.5 space-y-px text-[10px] text-gray-300">
                  <div>
                    <dt class="inline">Harga:</dt>
                    <dd class="inline">Rp{{ number_format($item->product->price,0,',','.') }}</dd>
                  </div>
                </dl>
              </div>

              <div class="flex flex-1 items-center justify-end gap-2">
                <form action="{{ route('cart.update', $item) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <label for="qty{{ $item->id }}" class="sr-only">Jumlah</label>
                  <input
                    type="number"
                    min="1"
                    name="quantity"
                    value="{{ $item->quantity }}"
                    id="qty{{ $item->id }}"
                    class="h-8 w-12 rounded-sm border-gray-200 bg-gray-50 p-0 text-center text-xs text-gray-600"
                  />
                </form>
                <form action="{{ route('cart.remove', $item) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button class="text-gray-600 transition hover:text-red-600">
                    <span class="sr-only">Remove item</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                  </button>
                </form>
              </div>
            </li>
            @empty
            <li class="text-white text-center">Keranjang kosong.</li>
            @endforelse
          </ul>

          <div class="mt-8 flex justify-end border-t border-gray-100 pt-8">
            <div class="w-screen max-w-lg space-y-4">
              <dl class="space-y-0.5 text-sm text-white">
                <div class="flex justify-between">
                  <dt>Subtotal</dt>
                  <dd>Rp{{ number_format($cartTotal,0,',','.') }}</dd>
                </div>
                <div class="flex justify-between !text-base font-medium">
                  <dt>Total</dt>
                  <dd>Rp{{ number_format($cartTotal,0,',','.') }}</dd>
                </div>
              </dl>

              <div class="flex justify-end">
                <form action="{{ route('orders.store') }}" method="POST">
                  @csrf
                  {{-- Pilih meja --}}
                  <select name="table_id" required class="mb-2 rounded bg-gray-800 text-white px-2 py-1">
                    <option value="">-- Pilih Meja --</option>
                    @foreach(\App\Models\Table::all() as $table)
                      <option value="{{ $table->id }}">{{ $table->number }}</option>
                    @endforeach
                  </select>
                  {{-- Kirim semua item cart --}}
                  @foreach($cartItems as $item)
                    <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                    <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}">
                  @endforeach
                  <button
                    type="submit"
                    class="block rounded-sm bg-blue-600 px-5 py-3 text-sm text-white transition hover:bg-blue-700 w-full"
                  >
                    Checkout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-app-layout>
