<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Cashier\OderController as CashierOrderController;
use App\Http\Controllers\Cashier\TransactionController as CashierTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;

// =================== ADMIN ===================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::resource('users', AdminUserController::class);
    Route::get('products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
});

// =================== CASHIER ===================
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function () {
    Route::get('/orders', [CashierOrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Cashier\OderController::class, 'show'])->name('cashier.orders.show');
    Route::post('/orders/{order}/complete', [CashierOrderController::class, 'complete'])->name('orders.complete');
    Route::get('/statistics', [CashierTransactionController::class, 'statistics'])->name('statistics');
    Route::resource('products', ProductController::class);
    Route::resource('tables', TableController::class);
    Route::resource('receipts', ReceiptController::class);
});

// =================== USER ===================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/order', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('user.order');
    Route::get('/order/create', [\App\Http\Controllers\User\OrderController::class, 'create'])->name('orders.create');
    Route::post('/order', [\App\Http\Controllers\User\OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/{order}/receipt', [\App\Http\Controllers\User\OrderController::class, 'downloadReceipt'])->name('order.receipt');
    Route::get('/order/{order}', [\App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/cart/{item}', [\App\Http\Controllers\User\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [\App\Http\Controllers\User\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/add/{product}', [\App\Http\Controllers\User\CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [\App\Http\Controllers\User\CartController::class, 'index'])->name('cart.index');
});

// =================== AUTH & HOME ===================
require __DIR__.'/auth.php';

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'cashier') {
            return redirect('/cashier/orders');
        } else {
            return redirect('/order');
        }
    }
    return redirect('/login');
});

Route::get('/dashboard', function () {
    // Redirect ke root, nanti root akan redirect sesuai role
    return redirect('/');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
