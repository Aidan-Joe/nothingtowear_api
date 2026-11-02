<?php
// app/Models/ClothingItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothingItem extends Model
{
    protected $table = 'Clothing_Items';

    protected $fillable = [
        'user_id',
        'category_id',
        'nama_item',
        'warna',
        'brand',
        'ukuran',
        'style_occasion',
        'tag',
        'url_gambar',
        'is_favorite',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}