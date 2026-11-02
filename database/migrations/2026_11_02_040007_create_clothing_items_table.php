<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Clothing_Items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('category_id')->index('category_id');
            $table->string('nama_item', 150);
            $table->string('warna', 50)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('ukuran', 20)->nullable();
            $table->string('style_occasion', 100)->nullable();
            $table->string('tag', 255)->nullable();
            $table->string('url_gambar', 255);
            $table->boolean('is_favorite')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'Clothing_Items_ibfk_1')
                  ->references('id')->on('Users')
                  ->cascadeOnDelete();

            $table->foreign('category_id', 'Clothing_Items_ibfk_2')
                  ->references('id')->on('Categories')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Clothing_Items');
    }
};