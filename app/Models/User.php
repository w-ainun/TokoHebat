<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — versi Yoga (VULNERABLE)
 *
 * BUG #3: Tidak ada cast 'hashed' pada password.
 *         Password disimpan apa adanya (plain text) ke database.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password', // disimpan plain text!
        'role',
    ];

    protected $hidden = [
        'remember_token',
        // NOTE: 'password' sengaja TIDAK di-hidden — Yoga lupa
    ];

    // NOTE: Tidak ada casts() untuk 'password' => 'hashed'
    //       Artinya password TIDAK di-hash otomatis oleh Laravel

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
