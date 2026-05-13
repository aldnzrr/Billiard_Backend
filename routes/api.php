<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tables', [TableController::class, 'index']);
Route::post('/tables', [TableController::class, 'store']);
Route::put('/tables/{id}', [TableController::class, 'update']);
Route::delete('/tables/{id}', [TableController::class, 'destroy']);
// Alamat buat orang mesen meja
Route::post('/bookings', [BookingController::class, 'store']);
// Melihat semua daftar booking
Route::get('/bookings', [BookingController::class, 'index']);
// Update status jadi dibayar
Route::put('/bookings/{id}/pay', [BookingController::class, 'pay']);
// User melakukan upload bukti transfer
Route::post('/bookings/{id}/upload-receipt', [BookingController::class, 'uploadReceipt']);

// User melihat tiket barcode-nya
Route::get('/bookings/{id}/ticket', [BookingController::class, 'getTicket']);
// Rute yang bisa diakses TANPA login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ... (Kodingan rute-rute Booking dan Tables kamu ada di bawah sini) ...