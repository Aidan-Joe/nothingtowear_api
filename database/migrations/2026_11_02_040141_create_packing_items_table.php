<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Packing_Items', function (Blueprint $table) {
            $table->integer('packing_id');
            $table->integer('item_id')->index('item_id');
            $table->integer('jumlah')->default(1);
            $table->boolean('is_packed')->default(false);
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['packing_id', 'item_id']);

            $table->foreign('packing_id', 'Packing_Items_ibfk_1')
                  ->references('id')->on('Packing_Lists')
                  ->cascadeOnDelete();

            $table->foreign('item_id', 'Packing_Items_ibfk_2')
                  ->references('id')->on('Clothing_Items')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Packing_Items');
    }
};