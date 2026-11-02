<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AI_Recommendations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('outfit_id')->index('outfit_id');
            $table->string('judul_rekomendasi', 150);
            $table->text('deskripsi_konteks')->nullable();
            $table->string('tag_cuaca', 50)->nullable();
            $table->boolean('is_saved')->default(false);
            $table->date('tanggal_rekomendasi');

            $table->foreign('user_id', 'AI_Recommendations_ibfk_1')
                  ->references('id')->on('Users')
                  ->cascadeOnDelete();

            $table->foreign('outfit_id', 'AI_Recommendations_ibfk_2')
                  ->references('id')->on('Outfits')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AI_Recommendations');
    }
};