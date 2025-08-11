<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Pakai alias
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ProfileController;

// =================== ROOT REDIRECT ===================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $role = $user->role ?? 'user';

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'cashier') {
            return redirect('/cashier/orders');
        } else {
            return redirect('/order');
        }
    }
    return redirect('/login');
});

// =================== DASHBOARD REDIRECT ===================
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role ?? 'user';

    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'cashier') {
        return redirect('/cashier/orders');
    } else {
        return redirect('/order');
    }
})->middleware(['auth'])->name('dashboard');

// =================== PROFILE ===================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =================== ADMIN ===================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Routes - tambahkan prefix 'admin.' ke semua nama route
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Users Routes
    Route::resource('users', AdminUserController::class);
    Route::get('users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
});

// =================== CASHIER ===================
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function () {
    Route::get('/orders', [CashierOrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('/orders/{order}', [CashierOrderController::class, 'show'])->name('cashier.orders.show');
    Route::post('/orders/{order}/complete', [CashierOrderController::class, 'complete'])->name('orders.complete');
    Route::get('/statistics', [CashierOrderController::class, 'statistics'])->name('statistics');
});

// =================== USER/CUSTOMER ===================
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Ubah ini agar menggunakan view spesifik untuk customer
    Route::get('/order', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('/order/create', [UserOrderController::class, 'create'])->name('orders.create');

    // Cart Routes
    Route::post('/cart/add/{product}', [UserCartController::class, 'add'])->name('cart.add'); // Fix namespace
    Route::get('/cart', [UserCartController::class, 'index'])->name('cart.index'); // Fix namespace
    Route::patch('/cart/{cartItem}', [UserCartController::class, 'update'])->name('cart.update'); // Fix namespace
    Route::delete('/cart/{cartItem}', [UserCartController::class, 'remove'])->name('cart.remove'); // Fix namespace
    Route::post('/cart/checkout', [UserCartController::class, 'checkout'])->name('cart.checkout'); // Fix namespace
});

// =================== PUBLIC ROUTES ===================
Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['auth']) // Hanya butuh auth, tanpa cek role
    ->name('products.index');
Route::get('/receipt/{order}', [ReceiptController::class, 'show'])->name('order.receipt'); // Fix nama route

// Tambahkan route test sederhana untuk debugging
Route::get('/test', function () {
    return [
        'status' => 'ok',
        'user' => auth()->check() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null
    ];
});

// Route untuk halaman utama user (menu & riwayat order)
Route::get('/order', function() {
    $products = \App\Models\Product::all(); // Tampilkan semua produk
    $orders = auth()->user()->orders()->latest()->get();
    $selectedTable = session('selected_table');

    return view('user.orders.index', compact('products', 'orders', 'selectedTable'));
})->middleware(['auth'])->name('user.orders.index');

// Route untuk keranjang belanja
Route::get('/cart', [App\Http\Controllers\User\CartController::class, 'index'])
    ->middleware(['auth'])
    ->name('cart.index');

// Route untuk menambahkan produk ke keranjang
Route::post('/cart/add/{product}', [App\Http\Controllers\User\CartController::class, 'add'])
    ->middleware(['auth'])
    ->name('cart.add');

// Route untuk memperbarui item keranjang
Route::patch('/cart/{cartItem}', [App\Http\Controllers\User\CartController::class, 'update'])
    ->middleware(['auth'])
    ->name('cart.update');

// Route untuk menghapus item dari keranjang
Route::delete('/cart/{cartItem}', [App\Http\Controllers\User\CartController::class, 'remove'])
    ->middleware(['auth'])
    ->name('cart.remove');

// Route untuk checkout (membuat order baru)
Route::post('/cart/checkout', [App\Http\Controllers\User\CartController::class, 'checkout'])
    ->middleware(['auth'])
    ->name('cart.checkout');

// Route untuk membuat order baru (form pemesanan)
Route::get('/order/create', [App\Http\Controllers\User\OrderController::class, 'create'])
    ->middleware(['auth'])
    ->name('orders.create');

// Route untuk menyimpan order baru
Route::post('/order', [App\Http\Controllers\User\OrderController::class, 'store'])
    ->middleware(['auth'])
    ->name('orders.store');

// Route untuk melihat detail order
Route::get('/orders/{order}', [App\Http\Controllers\User\OrderController::class, 'show'])
    ->middleware(['auth'])
    ->name('orders.show');

// Route untuk melihat struk/receipt
Route::get('/receipt/{order}', [App\Http\Controllers\ReceiptController::class, 'show'])
    ->middleware(['auth'])
    ->name('order.receipt');

require __DIR__.'/auth.php';
