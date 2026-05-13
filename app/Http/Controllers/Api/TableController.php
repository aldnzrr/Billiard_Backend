<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table; // Ini untuk memanggil data Meja
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        // Mengambil semua data meja dari database
        $tables = Table::all();

        // Mengirimkan data ke Postman dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Daftar Meja Golden Break',
            'data'    => $tables
        ], 200);
    }

    public function store(Request $request)
    {
        // 1. Validasi input: Memastikan data yang dikirim benar
        $request->validate([
            'table_number'   => 'required|unique:tables',
            'type'           => 'required|in:regular,vip',
            'price_per_hour' => 'required|integer',
        ]);

        // 2. Simpan data ke database
        $table = Table::create([
            'table_number'   => $request->table_number,
            'type'           => $request->type,
            'price_per_hour' => $request->price_per_hour,
        ]);

        // 3. Respon sukses
        return response()->json([
            'success' => true,
            'message' => 'Meja Berhasil Ditambahkan!',
            'data'    => $table
        ], 201);
    }

    // Fungsi untuk Update data (Edit Meja)
    public function update(Request $request, $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Meja tidak ditemukan'], 404);
        }

        $table->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Meja berhasil diperbarui!',
            'data'    => $table
        ]);
    }

    // Fungsi untuk Delete data (Hapus Meja)
    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Meja tidak ditemukan'], 404);
        }

        $table->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus!'
        ]);
    }
}