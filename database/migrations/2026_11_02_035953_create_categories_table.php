<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_kategori', 50);
            $table->string('ikon_url', 255)->nullable();
            $table->integer('urutan_tampil');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Categories');
    }
};