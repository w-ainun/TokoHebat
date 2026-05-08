<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — versi FIXED ✅
|--------------------------------------------------------------------------
|
| Perbaikan dari kode Yoga:
| - Route auth (register/login) tetap public.
| - Route logout dilindungi auth:sanctum (harus login dulu).
| - Route admin dilindungi auth:sanctum + IsAdmin middleware.
| - Route user orders dilindungi auth:sanctum.
|
*/

// ============================
// AUTH — public routes
// ============================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ============================
// PROTECTED — harus login
// ============================
Route::middleware('auth:sanctum')->group(function () {

    // Logout — butuh token valid
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Orders — hanya bisa lihat/buat order sendiri
    Route::get('/orders',  [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    // ============================
    // ADMIN — ✅ auth:sanctum + IsAdmin
    // ============================
    // Harus login DAN harus punya role 'admin'.
    // User biasa akan mendapat 403 Forbidden.
    Route::prefix('admin')->middleware(IsAdmin::class)->group(function () {
        Route::get('/users',            [AdminController::class, 'listUsers']);
        Route::get('/users/{id}',       [AdminController::class, 'showUser']);
        Route::delete('/users/{id}',    [AdminController::class, 'deleteUser']);

        Route::get('/orders',           [AdminController::class, 'listOrders']);

        Route::get('/products',         [AdminController::class, 'listProducts']);
        Route::post('/products',        [AdminController::class, 'storeProduct']);
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
    });
});
