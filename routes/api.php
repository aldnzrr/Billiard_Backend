<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\BookingController;

// ==========================================
// PUBLIC ROUTE (TIDAK DIUBAH)
// ==========================================

// REGISTER
Route::post('/register', [AuthController::class, 'register']);

// LOGIN
Route::post('/login', [AuthController::class, 'login']);

// GET TABLES
Route::get('/tables', [TableController::class, 'index']);


// ==========================================
// RESERVATION (PUBLIC ACCESSIBLE POST ROUTE)
// ==========================================
// PERBAIKAN: Diubah dari /reservation menjadi /bookings agar sinkron dengan Android
Route::post('/bookings', [BookingController::class, 'store']);


// ==========================================
// LOGIN REQUIRED (SANCTUM MIDDLEWARE)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // GET USER LOGIN
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ==========================================
    // ADMIN + USER
    // ==========================================

    Route::middleware('role:admin,user')->group(function () {

        // UPLOAD RECEIPT
        Route::post(
            '/bookings/{id}/upload-receipt',
            [BookingController::class, 'uploadReceipt']
        );

        // GET TICKET
        Route::get(
            '/bookings/{id}/ticket',
            [BookingController::class, 'getTicket']
        );
    });

    // ==========================================
    // ADMIN ONLY
    // ==========================================

    Route::middleware('role:admin')->group(function () {

        // TABLES
        Route::post(
            '/tables',
            [TableController::class, 'store']
        );

        Route::put(
            '/tables/{id}',
            [TableController::class, 'update']
        );

        Route::delete(
            '/tables/{id}',
            [TableController::class, 'destroy']
        );

        // BOOKINGS
        Route::get(
            '/bookings',
            [BookingController::class, 'index']
        );

        // PAYMENT
        Route::put(
            '/bookings/{id}/pay',
            [BookingController::class, 'pay']
        );
    });

});