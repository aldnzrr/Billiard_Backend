<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================
    // 1. FUNGSI REGISTER (DAFTAR AKUN BARU)
    // ==========================================
    public function register(Request $request)
    {
        // Validasi inputan dari form Figma
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users', // Email ga boleh kembar
            'password' => 'required|min:6' // Password minimal 6 huruf/angka
        ]);

        // Simpan data user baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) // Enkripsi password
        ]);

        // Buatkan Token (Tiket masuk)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'data' => $user,
            'token' => $token
        ]);
    }

    // ==========================================
    // 2. FUNGSI LOGIN (MENGGUNAKAN USERNAME)
    // ==========================================
    public function login(Request $request)
    {
        // 1. Validasi bahwa 'username' dan 'password' wajib diisi
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // 2. Cari berdasarkan kolom 'name' di database menggunakan inputan 'username'
        $user = User::where('name', $request->username)->first();

        // 3. Cek apakah usernamenya ada DAN passwordnya cocok?
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Kalau salah satu salah, tolak!
            return response()->json([
                'message' => 'Username atau Password salah!'
            ], 401);
        }

        // 4. Kalau benar, buatkan Token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token
        ]);
    }

    // ==========================================
    // 3. FUNGSI LOGOUT (KELUAR APLIKASI)
    // ==========================================
    public function logout(Request $request)
    {
        // Hapus (bakar) token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil!'
        ]);
    }
}