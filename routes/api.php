<?php

use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\CheckSanctumToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthenticationController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes for Product CRUD operations
Route::resource('products', ProductController::class)
    ->middleware(['auth:sanctum', IsAdmin::class]);

// Non-admin routes (read-only)
Route::resource('products', ProductController::class)
    ->only(['index', 'show'])
   ;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart'])->middleware(AuthenticateUser::class); // Add item to cart
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart']); // Remove item from cart
    Route::get('/cart/view', [CartController::class, 'viewCart']); // View cart
    Route::patch('/cart/update/{productId}', [CartController::class, 'updateCart']); // View cart
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders/place',[OrderController::class,'placeOrder']);
    // Route::get('/orders',[OrderController::class,'viewOrders'])->name('orders.page');
});
Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'viewOrders'])->name('orders.page');

Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');