<?php
// app/Models/PackingList.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    protected $table = 'Packing_Lists';

    protected $fillable = [
        'user_id',
        'nama_perjalanan',
        'destinasi',
        'tgl_mulai',
        'tgl_selesai',
    ];

    public function items()
    {
        return $this->belongsToMany(ClothingItem::class, 'Packing_Items', 'packing_id', 'item_id')
                    ->withPivot(['jumlah', 'is_packed', 'updated_at']);
    }
}