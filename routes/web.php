<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
})->name('index');



// Show Login Page
Route::get('/login', function () {
    return view('front-end.login'); 
})->name('login.page');

// Process Login (redirect for web)
// Route::post('/login/web', [AuthenticationController::class, 'webLogin'])->name('login.web');
Route::get('/orders', function () {
    return view('front-end.orders');
})->name('orders');