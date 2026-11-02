<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Calendar_Entries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('outfit_id')->index('outfit_id');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('lokasi', 150)->nullable();
            $table->string('event', 150)->nullable();
            $table->string('kondisi_cuaca', 50)->nullable();
            $table->float('suhu')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id', 'Calendar_Entries_ibfk_1')
                  ->references('id')->on('Users')
                  ->cascadeOnDelete();

            $table->foreign('outfit_id', 'Calendar_Entries_ibfk_2')
                  ->references('id')->on('Outfits')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Calendar_Entries');
    }
};