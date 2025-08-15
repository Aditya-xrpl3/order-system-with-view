<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ReceiptController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Products
    Route::apiResource('products', ProductController::class);

    // Tables
    Route::apiResource('tables', TableController::class);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add/{product}', [CartController::class, 'add']);
    Route::patch('/cart/{cartItem}', [CartController::class, 'update']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);

    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete']);

    // Receipts
    Route::get('/receipts/{order}', [ReceiptController::class, 'show']);
    Route::get('/receipts/{order}/pdf', [ReceiptController::class, 'generatePdf']);

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('/statistics', [OrderController::class, 'statistics']);
    });

    // Cashier routes
    Route::middleware('role:cashier')->prefix('cashier')->group(function () {
        Route::get('/pending-orders', [OrderController::class, 'pendingOrders']);
        Route::get('/statistics', [OrderController::class, 'statistics']);
    });
});
