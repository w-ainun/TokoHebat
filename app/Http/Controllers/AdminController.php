<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * AdminController — versi Yoga (VULNERABLE)
 *
 * BUG #2 (IDOR / Broken Access Control):
 *   - Semua endpoint admin bisa diakses SIAPAPUN.
 *   - Tidak ada middleware auth.
 *   - Tidak ada pengecekan role user.
 *   - User biasa bisa akses /api/admin/users/1, /api/admin/users/2, dst.
 *     hanya dengan mengganti angka di URL.
 */
class AdminController extends Controller
{
    /**
     * Lihat semua users — tanpa cek apakah yang request adalah admin.
     *
     * ❌ Siapapun bisa lihat data semua user (termasuk password plain text!)
     */
    public function listUsers()
    {
        $users = User::all();

        return response()->json([
            'message' => 'Daftar semua user',
            'data'    => $users,
        ]);
    }

    /**
     * Lihat detail user tertentu — IDOR vulnerability.
     *
     * ❌ Cukup ganti {id} di URL untuk lihat data user manapun.
     *    Tidak ada pengecekan siapa yang sedang login.
     */
    public function showUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Bahkan menampilkan password karena tidak di-hidden di model!
        return response()->json([
            'message' => 'Detail user',
            'data'    => $user,
        ]);
    }

    /**
     * Hapus user — tanpa cek otorisasi.
     *
     * ❌ User biasa bisa menghapus user lain!
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus',
        ]);
    }

    /**
     * Lihat semua orders.
     *
     * ❌ Tidak ada autentikasi/otorisasi.
     */
    public function listOrders()
    {
        $orders = Order::with(['user', 'product'])->get();

        return response()->json([
            'message' => 'Daftar semua order',
            'data'    => $orders,
        ]);
    }

    /**
     * Lihat semua products.
     */
    public function listProducts()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Daftar semua produk',
            'data'    => $products,
        ]);
    }

    /**
     * Tambah produk baru — tanpa cek admin.
     *
     * ❌ User biasa bisa menambah produk!
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product = Product::create($request->only(['name', 'description', 'price', 'stock']));

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product,
        ], 201);
    }

    /**
     * Hapus produk — tanpa cek admin.
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}
