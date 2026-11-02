<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Outfits', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('nama', 150);
            $table->string('mood_event', 100)->nullable();
            $table->string('metode_pembuatan', 50)->comment('Manual, Key Item AI, Full AI Builder');
            $table->string('preview_url', 255)->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'Outfits_ibfk_1')
                  ->references('id')->on('Users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Outfits');
    }
};