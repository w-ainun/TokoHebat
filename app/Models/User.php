<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model User — versi FIXED 
 *
 * Perbaikan dari kode Yoga:
 * 1. Menambahkan HasApiTokens untuk autentikasi token via Sanctum.
 * 2. Cast 'password' => 'hashed' agar Laravel otomatis hash
 *    password setiap kali di-set (menggunakan bcrypt).
 * 3. 'password' masuk ke $hidden agar tidak bocor di response JSON.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     *  FIX #3: Password di-hidden dari JSON response.
     *    Yoga lupa memasukkan 'password' ke sini,
     *    sehingga password ikut tampil di response API.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     *  FIX #3: Cast 'hashed' membuat password otomatis di-hash
     *    menggunakan bcrypt setiap kali password di-set.
     *    Yoga tidak punya casts() ini sama sekali.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
