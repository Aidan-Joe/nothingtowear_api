<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'Users';

    // Tabel Users di db_nothingtowear.sql hanya memiliki created_at (tanpa updated_at)
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'password_updated_at',
        'avatar_url',
        'lokasi_default',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Override kolom password bawaan Laravel ke password_hash
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function clothingItems()
    {
        return $this->hasMany(ClothingItem::class, 'user_id');
    }

    public function outfits()
    {
        return $this->hasMany(Outfit::class, 'user_id');
    }
}