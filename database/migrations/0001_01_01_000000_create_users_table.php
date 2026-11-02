<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->integer('id', true); // int NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->string('nama', 100);
            $table->string('email', 100)->unique('email');
            $table->string('password_hash', 255);
            $table->dateTime('password_updated_at')->nullable();
            $table->string('avatar_url', 255)->nullable();
            $table->string('lokasi_default', 150)->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('Users');
    }
};