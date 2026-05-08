<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — versi Yoga (VULNERABLE)
|--------------------------------------------------------------------------
|
| BUG #2: Route admin TIDAK dilindungi middleware auth maupun
|         pengecekan role. Siapapun bisa akses endpoint admin
|         tanpa login, cukup tahu URL-nya.
|
*/

// ============================
// AUTH (tanpa proteksi apapun)
// ============================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout']);

// ============================
// ADMIN — ❌ TANPA MIDDLEWARE!
// ============================
// Seharusnya dilindungi auth + role check, tapi Yoga lupa.
// User biasa (bahkan tanpa login!) bisa akses semua endpoint ini.
Route::prefix('admin')->group(function () {
    Route::get('/users',          [AdminController::class, 'listUsers']);
    Route::get('/users/{id}',     [AdminController::class, 'showUser']);
    Route::delete('/users/{id}',  [AdminController::class, 'deleteUser']);

    Route::get('/orders',         [AdminController::class, 'listOrders']);

    Route::get('/products',       [AdminController::class, 'listProducts']);
    Route::post('/products',      [AdminController::class, 'storeProduct']);
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
});

// ============================
// USER ORDERS
// ============================
Route::get('/orders',  [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
