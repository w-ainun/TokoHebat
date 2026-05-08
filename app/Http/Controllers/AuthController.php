<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController — versi FIXED 
 *
 * Perbaikan dari kode Yoga:
 * 1. Login: Menggunakan Auth::attempt() untuk verifikasi email + password.
 * 2. Register: Password otomatis di-hash via cast 'hashed' di Model.
 * 3. Token: Menggunakan Sanctum token untuk autentikasi stateless.
 * 4. Logout: Menghapus token aktif, bukan cuma return string.
 */
class AuthController extends Controller
{
    /**
     * Register user baru.
     *
     * ✅ FIX #3: Password otomatis di-hash oleh cast 'hashed' di User model.
     *    Kode Yoga: password disimpan plain text.
     *    Kode Fix:  model cast 'hashed' otomatis bcrypt sebelum save.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // otomatis di-hash oleh cast 'hashed'
            'role'     => 'user',
        ]);

        // Buat token Sanctum untuk autentikasi selanjutnya
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Login user.
     *
     * FIX #1: Menggunakan Auth::attempt() yang memverifikasi
     *    email DAN password secara bersamaan.
     *
     *    Kode Yoga: hanya cari User::where('email', ...) lalu langsung
     *               return "berhasil" — password diabaikan sepenuhnya.
     *    Kode Fix:  Auth::attempt() akan hash password input, lalu
     *               bandingkan dengan hash di database. Kalau tidak
     *               cocok → return 401.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Auth::attempt() melakukan 3 hal sekaligus:
        //    1. Cari user berdasarkan email
        //    2. Hash password input lalu bandingkan dengan hash di DB
        //    3. Return false kalau email tidak ada ATAU password salah
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();

        // Buat token Sanctum untuk request selanjutnya
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Logout — hapus token yang sedang aktif.
     *
     * Kode Yoga: cuma return string, tidak ada proses apapun.
     *    Kode Fix:  hapus Sanctum token supaya tidak bisa dipakai lagi.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
