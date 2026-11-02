<?php
// app/Models/CalendarEntry.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntry extends Model
{
    protected $table = 'Calendar_Entries';

    protected $fillable = [
        'user_id',
        'outfit_id',
        'tanggal',
        'waktu',
        'lokasi',
        'event',
        'kondisi_cuaca',
        'suhu',
    ];

    protected $casts = [
        'suhu' => 'float',
    ];

    public function outfit()
    {
        return $this->belongsTo(Outfit::class, 'outfit_id');
    }
}