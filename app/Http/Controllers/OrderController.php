<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * OrderController — versi Yoga
 *
 * Controller untuk user biasa membuat dan melihat order.
 */
class OrderController extends Controller
{
    /**
     * Buat order baru.
     * (user_id diambil dari request body — bukan dari auth session)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $order = Order::create([
            'user_id'     => $request->user_id,
            'product_id'  => $request->product_id,
            'quantity'    => $request->quantity,
            'total_price' => $product->price * $request->quantity,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Order berhasil dibuat',
            'data'    => $order->load('product'),
        ], 201);
    }

    /**
     * Lihat order berdasarkan user_id (dari query param, bukan auth).
     */
    public function index(Request $request)
    {
        $orders = Order::with(['product'])
            ->where('user_id', $request->query('user_id'))
            ->get();

        return response()->json([
            'message' => 'Daftar order',
            'data'    => $orders,
        ]);
    }
}
