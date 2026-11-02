<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('calendar_id')->nullable()->index('calendar_id');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->string('tipe_notifikasi', 50)->comment('OOTD Reminder, Weather Alert, dll');
            $table->boolean('is_read')->default(false);
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('user_id', 'Notifications_ibfk_1')
                  ->references('id')->on('Users')
                  ->cascadeOnDelete();

            $table->foreign('calendar_id', 'Notifications_ibfk_2')
                  ->references('id')->on('Calendar_Entries')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Notifications');
    }
};