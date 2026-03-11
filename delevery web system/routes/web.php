<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\DriverController;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::redirect('/', '/login');

Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users',          [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',   [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',         [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',   [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',        [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',     [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{user}/toggle',[AdminController::class, 'toggleUser'])->name('users.toggle');

    // Orders
    Route::get('/orders',                      [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}',              [AdminController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/assign',      [AdminController::class, 'assignDriver'])->name('orders.assign');
    Route::post('/orders/{order}/status',      [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

// ─── Seller ──────────────────────────────────────────────────────────────────
Route::prefix('seller')->name('seller.')->middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders',                    [SellerController::class, 'orders'])->name('orders');
    Route::get('/orders/create',             [SellerController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders',                   [SellerController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}',            [SellerController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{order}/edit',       [SellerController::class, 'editOrder'])->name('orders.edit');
    Route::put('/orders/{order}',            [SellerController::class, 'updateOrder'])->name('orders.update');
});

// ─── Driver ──────────────────────────────────────────────────────────────────
Route::prefix('driver')->name('driver.')->middleware(['auth', 'role:driver'])->group(function () {
    Route::get('/dashboard',                       [DriverController::class, 'dashboard'])->name('dashboard');
    Route::get('/deliveries',                      [DriverController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/{order}',              [DriverController::class, 'showDelivery'])->name('deliveries.show');
    Route::post('/deliveries/{order}/status',      [DriverController::class, 'updateStatus'])->name('deliveries.status');
});
