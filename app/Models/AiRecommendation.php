<?php
// app/Models/AiRecommendation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    protected $table = 'AI_Recommendations';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'outfit_id',
        'judul_rekomendasi',
        'deskripsi_konteks',
        'tag_cuaca',
        'is_saved',
        'tanggal_rekomendasi',
    ];

    protected $casts = ['is_saved' => 'boolean'];

    public function outfit()
    {
        return $this->belongsTo(Outfit::class, 'outfit_id');
    }
}