<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * AdminController — versi FIXED ✅
 *
 * Perbaikan dari kode Yoga:
 * - Controller ini sekarang HANYA bisa diakses melalui route yang
 *   dilindungi middleware 'auth:sanctum' + 'is_admin'.
 * - Tidak perlu cek role di setiap method karena middleware sudah handle.
 * - Password tidak bocor karena $hidden di model sudah diset.
 */
class AdminController extends Controller
{
    /**
     * Lihat semua users.
     *
     * ✅ Aman karena middleware auth:sanctum + is_admin sudah
     *    memastikan hanya admin yang bisa akses.
     *    Password tidak tampil karena sudah di-$hidden di model.
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
     * Lihat detail user tertentu.
     *
     * ✅ Tidak ada IDOR karena hanya admin yang bisa akses endpoint ini.
     */
    public function showUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail user',
            'data'    => $user,
        ]);
    }

    /**
     * Hapus user.
     *
     * ✅ Hanya admin yang bisa menghapus user.
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
     * ✅ Dilindungi middleware.
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
     * Tambah produk baru.
     *
     * ✅ Hanya admin yang bisa menambah produk.
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
     * Hapus produk.
     *
     * ✅ Hanya admin yang bisa menghapus produk.
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
