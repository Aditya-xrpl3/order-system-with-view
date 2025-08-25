<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ProfileController;

// =================== ROOT REDIRECT - DIPERBAIKI ===================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $role = $user->role ?? 'user';

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'cashier') {
            return redirect('/cashier/orders');
        } else {
            // CUSTOMER REDIRECT KE MENU, BUKAN PRODUCTS
            return redirect('/menu');
        }
    }
    return redirect('/login');
});

// =================== DASHBOARD REDIRECT - DIPERBAIKI ===================
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->role ?? 'user';

    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'cashier') {
        return redirect('/cashier/orders');
    } else {
        // CUSTOMER REDIRECT KE MENU
        return redirect('/menu');
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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('users', AdminUserController::class);
});

// =================== CASHIER ===================
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function () {
    Route::get('/orders', [CashierOrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('/orders/{order}', [CashierOrderController::class, 'show'])->name('cashier.orders.show');
    Route::post('/orders/{order}/complete', [CashierOrderController::class, 'complete'])->name('orders.complete');
    Route::get('/statistics', [CashierOrderController::class, 'statistics'])->name('statistics');
    Route::get('/statistics/export', [CashierOrderController::class, 'exportStatistics'])->name('statistics.export');
});

// =================== USER/CUSTOMER ===================
    Route::get('/menu', [App\Http\Controllers\User\MenuController::class, 'index'])->name('menu');

    // Cart routes
    Route::get('/cart', [UserCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [UserCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/increase/{product}', [UserCartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/decrease/{product}', [UserCartController::class, 'decrease'])->name('cart.decrease');
    Route::post('/cart/remove/{product}', [UserCartController::class, 'removeByProductId'])->name('cart.remove');
    Route::post('/cart/checkout', [UserCartController::class, 'checkout'])->name('cart.checkout');

    // Order routes
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/order', [UserOrderController::class, 'store'])->name('orders.store');

// =================== PUBLIC ROUTES ===================
Route::get('/tables', [TableController::class, 'index'])->name('tables.index');

// ROUTE PRODUCTS UNTUK ADMIN SAJA - BUKAN PUBLIC
Route::get('/admin/products-public', [ProductController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('products.index');

Route::get('/receipt/{order}', [ReceiptController::class, 'show'])->name('order.receipt');
Route::get('/receipt/{order}/download', [ReceiptController::class, 'downloadPdf'])->name('receipt.download');

Route::post('/tables/{table}/set-empty', [TableController::class, 'setEmpty'])->name('tables.setEmpty');

require __DIR__.'/auth.php';
