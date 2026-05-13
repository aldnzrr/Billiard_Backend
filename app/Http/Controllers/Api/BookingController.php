<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Table;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Ambil data meja untuk tahu harganya
        $table = Table::find($request->table_id);

        if (!$table) {
            return response()->json(['message' => 'Meja tidak ada'], 404);
        }

        // 2. Hitung total harga (harga meja x durasi)
        $totalPrice = $table->price_per_hour * $request->duration;

        // 3. Simpan data booking
        $booking = Booking::create([
            'table_id' => $request->table_id,
            'customer_name' => $request->customer_name,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat!',
            'data' => $booking
        ], 201);
    }

    public function index()
    {
        // Mengambil semua data booking beserta informasi mejanya
        $bookings = Booking::with('table')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Booking',
            'data' => $bookings
        ]);
    }

    public function pay($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        $booking->update(['status' => 'paid']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Berhasil!',
            'data' => $booking
        ]);
    }

    // 1. Fungsi Simpan Bukti Transfer (Sesuai Halaman 'Upload Bukti' di Figma)
    public function uploadReceipt(Request $request, $id)
    {
        $booking = Booking::find($id); // Cari data bookingnya

        // Baris ini menangkap file foto yang diupload dari Figma
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('receipts'); // Simpan foto ke folder receipts
            $booking->receipt_image = $path; // Simpan nama file ke database
            $booking->status = 'waiting'; // Ubah status jadi 'Menunggu Verifikasi'
            $booking->save();
        }

        return response()->json(['message' => 'Struk berhasil dikirim!']);
    }

    // 2. Fungsi Ambil Data Tiket (Sesuai Halaman 'Barcode' di Figma)
    public function getTicket($id)
    {
        $booking = Booking::where('id', $id)->first();

        // Jika belum lunas, jangan kasih barcode dulu
        if ($booking->status !== 'paid') {
            return response()->json(['message' => 'Bayar dulu bos!'], 403);
        }

        // Kirim data Meja, Jam, dan Kode Barcode ke Figma
        return response()->json([
            'table' => $booking->table_id,
            'time' => $booking->start_time,
            'barcode' => $booking->ticket_code // Ini yang akan jadi gambar Barcode
        ]);
    }
}