<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notifications';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Tabel Notifications hanya memiliki created_at

    protected $fillable = [
        'user_id',
        'calendar_id',
        'judul',
        'pesan',
        'tipe_notifikasi',
        'is_read',
    ];

    protected $casts = ['is_read' => 'boolean'];
}