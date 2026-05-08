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
     * ✅ FIX #3: Password sekarang otomatis di-hash oleh cast 'hashed'
     *    di model User. Meskipun kita tulis string plain di sini,
     *    saat User::create() dipanggil, Laravel akan otomatis
     *    bcrypt password sebelum menyimpan ke database.
     */
    public function run(): void
    {
        // ==========================================
        // USERS — password otomatis di-hash oleh model cast ✅
        // ==========================================
        $admin = User::create([
            'name'     => 'Admin TokoHebat',
            'email'    => 'admin@tokohebat.com',
            'password' => 'admin123',       // ✅ otomatis di-hash oleh cast 'hashed'
            'role'     => 'admin',
        ]);

        $budi = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@gmail.com',
            'password' => 'budi1234',       // ✅ otomatis di-hash
            'role'     => 'user',
        ]);

        $siti = User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@gmail.com',
            'password' => 'sitiaman99',     // ✅ otomatis di-hash
            'role'     => 'user',
        ]);

        $andi = User::create([
            'name'     => 'Andi Pratama',
            'email'    => 'andi@gmail.com',
            'password' => 'andi5678',       // ✅ otomatis di-hash
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
