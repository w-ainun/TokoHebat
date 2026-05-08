<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * AuthController — versi Yoga (VULNERABLE)
 *
 * BUG #1 (register): Password disimpan PLAIN TEXT — tanpa Hash::make()
 * BUG #2 (login):    Hanya cek email, TIDAK verifikasi password sama sekali.
 *                    Siapapun bisa login ke akun orang lain asal tahu email-nya.
 */
class AuthController extends Controller
{
    /**
     * Register user baru.
     *
     * BUG: password langsung disimpan tanpa di-hash.
     *      Kalau database bocor, semua password user terekspos.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // ❌ BUG #3: Password disimpan PLAIN TEXT!
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,  // Seharusnya Hash::make($request->password)
            'role'     => 'user',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user'    => $user,
        ], 201);
    }

    /**
     * Login user.
     *
     * BUG: Hanya cari user berdasarkan email, lalu langsung anggap "berhasil login".
     *      Password yang dikirim TIDAK pernah dicocokkan.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // ❌ BUG #1: Cuma cari user by email — password DIABAIKAN!
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan',
            ], 401);
        }

        // Langsung return "login berhasil" tanpa cek password
        return response()->json([
            'message' => 'Login berhasil',
            'user'    => $user,
        ]);
    }

    /**
     * Logout — dummy (tidak ada session/token management)
     */
    public function logout()
    {
        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
