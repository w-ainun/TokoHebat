<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * BUG #3 terlihat jelas di sini:
     * Password disimpan sebagai PLAIN TEXT ke database.
     * Siapapun yang punya akses ke database bisa baca password semua user.
     */
    public function run(): void
    {
        // ==========================================
        // USERS — password disimpan PLAIN TEXT!
        // ==========================================
        $admin = User::create([
            'name'     => 'Admin TokoHebat',
            'email'    => 'admin@tokohebat.com',
            'password' => 'admin123',       // ❌ PLAIN TEXT!
            'role'     => 'admin',
        ]);

        $budi = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@gmail.com',
            'password' => 'budi1234',       // ❌ PLAIN TEXT!
            'role'     => 'user',
        ]);

        $siti = User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@gmail.com',
            'password' => 'sitiaman99',     // ❌ PLAIN TEXT!
            'role'     => 'user',
        ]);

        $andi = User::create([
            'name'     => 'Andi Pratama',
            'email'    => 'andi@gmail.com',
            'password' => 'andi5678',       // ❌ PLAIN TEXT!
            'role'     => 'user',
        ]);

        // ==========================================
        // PRODUCTS
        // ==========================================
        $product1 = Product::create([
            'name'        => 'Kopi Toraja Premium',
            'description' => 'Kopi arabika asli Toraja, roasting medium',
            'price'       => 85000,
            'stock'       => 50,
        ]);

        $product2 = Product::create([
            'name'        => 'Sambal Roa Manado',
            'description' => 'Sambal ikan roa khas Manado, level pedas',
            'price'       => 45000,
            'stock'       => 100,
        ]);

        $product3 = Product::create([
            'name'        => 'Dodol Garut',
            'description' => 'Dodol tradisional khas Garut, rasa original',
            'price'       => 30000,
            'stock'       => 75,
        ]);

        $product4 = Product::create([
            'name'        => 'Keripik Tempe Malang',
            'description' => 'Keripik tempe renyah khas Malang',
            'price'       => 25000,
            'stock'       => 120,
        ]);

        // ==========================================
        // ORDERS
        // ==========================================
        Order::create([
            'user_id'     => $budi->id,
            'product_id'  => $product1->id,
            'quantity'    => 2,
            'total_price' => 170000,
            'status'      => 'completed',
        ]);

        Order::create([
            'user_id'     => $siti->id,
            'product_id'  => $product2->id,
            'quantity'    => 3,
            'total_price' => 135000,
            'status'      => 'pending',
        ]);

        Order::create([
            'user_id'     => $andi->id,
            'product_id'  => $product3->id,
            'quantity'    => 1,
            'total_price' => 30000,
            'status'      => 'completed',
        ]);
    }
}
