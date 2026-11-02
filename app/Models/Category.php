<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Categories';
    public $timestamps = false; // Tidak ada created_at & updated_at

    protected $fillable = ['nama_kategori', 'ikon_url', 'urutan_tampil'];
}