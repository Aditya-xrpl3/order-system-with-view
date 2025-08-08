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

        // Gunakan role langsung sebagai string
        $role = $user->role;

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'cashier') {
            return redirect('/cashier/orders');
        } elseif ($role === 'customer') {
            return redirect('/order');
        }
    }
    return redirect('/login');
});

// =================== DASHBOARD REDIRECT ===================
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role;

    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'cashier') {
        return redirect('/cashier/orders');
    } elseif ($role === 'customer') {
        return redirect('/order');
    }

    // Fallback jika role tidak dikenali
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

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
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/receipt/{order}', [ReceiptController::class, 'show'])->name('order.receipt'); // Fix nama route

require __DIR__.'/auth.php';
