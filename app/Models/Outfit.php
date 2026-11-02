<?php
// app/Models/Outfit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    protected $table = 'Outfits';

    protected $fillable = [
        'user_id',
        'nama',
        'mood_event',
        'metode_pembuatan',
        'preview_url',
        'is_favorite',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    public function items()
    {
        return $this->belongsToMany(ClothingItem::class, 'Outfit_Items', 'outfit_id', 'item_id')
                    ->withPivot(['pos_x', 'pos_y', 'z_index', 'scale', 'is_key_item', 'updated_at'])
                    ->orderByPivot('z_index', 'asc');
    }
}