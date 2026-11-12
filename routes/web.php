<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Rutas Web y Fortify / Autenticación - SIGR (Línea Base v1.0.0)
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

// Rutas de invitados (Autenticación / Fortify)
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Rutas protegidas por sesión autenticada
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Módulo de Menú (Platos y Categorías)
    Route::prefix('menu')->name('menu.')->group(function (): void {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::post('/categories', [MenuController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [MenuController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [MenuController::class, 'destroyCategory'])->name('categories.destroy');

        Route::post('/dishes', [MenuController::class, 'storeDish'])->name('dishes.store');
        Route::put('/dishes/{dish}', [MenuController::class, 'updateDish'])->name('dishes.update');
        Route::delete('/dishes/{dish}', [MenuController::class, 'destroyDish'])->name('dishes.destroy');
    });

    // Módulo de Comandas / Órdenes
    Route::prefix('orders')->name('orders.')->group(function (): void {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    });

    // Módulo de Reservaciones y Disponibilidad de Mesas
    Route::prefix('reservations')->name('reservations.')->group(function (): void {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/availability', [ReservationController::class, 'checkAvailability'])->name('availability');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::patch('/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('update-status');
    });

    // Módulo de Caja Registradora
    Route::prefix('cash-registers')->name('cash-registers.')->group(function (): void {
        Route::get('/', [CashRegisterController::class, 'index'])->name('index');
        Route::post('/open', [CashRegisterController::class, 'open'])->name('open');
        Route::patch('/{cashRegister}/close', [CashRegisterController::class, 'close'])->name('close');
    });

    // Módulo de Comprobantes / Facturación y Propina Voluntaria
    Route::prefix('receipts')->name('receipts.')->group(function (): void {
        Route::get('/', [ReceiptController::class, 'index'])->name('index');
        Route::get('/{receipt}', [ReceiptController::class, 'show'])->name('show');
        Route::post('/orders/{order}/generate', [ReceiptController::class, 'generate'])->name('generate');
    });
});

