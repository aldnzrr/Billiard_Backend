<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\AuthController;

// ==========================================
// 1. ROUTE PUBLIK (Bisa diakses TANPA login)
// ==========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/tables', [TableController::class, 'index']); // Semua orang bisa lihat daftar meja


// ==========================================
// 2. ROUTE YANG WAJIB LOGIN (Semua Role)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Ambil data profil user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Jalur yang bisa diakses oleh ADMIN dan USER biasa
    Route::middleware('role:admin,user')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']); // Alamat buat orang mesen meja
        Route::post('/bookings/{id}/upload-receipt', [BookingController::class, 'uploadReceipt']); // User upload bukti transfer
        Route::get('/bookings/{id}/ticket', [BookingController::class, 'getTicket']); // User melihat tiket barcode-nya
    });

    // ==========================================
    // 3. ROUTE KHUSUS ADMIN SAJA
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Kelola Meja Billiard
        Route::post('/tables', [TableController::class, 'store']);
        Route::put('/tables/{id}', [TableController::class, 'update']);
        Route::delete('/tables/{id}', [TableController::class, 'destroy']);
        
        // Kelola Bookingan
        Route::get('/bookings', [BookingController::class, 'index']); // Melihat semua daftar booking (hanya admin)
        Route::put('/bookings/{id}/pay', [BookingController::class, 'pay']); // Update status jadi dibayar
    });

});