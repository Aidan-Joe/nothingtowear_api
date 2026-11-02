<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Outfit_Items', function (Blueprint $table) {
            $table->integer('outfit_id');
            $table->integer('item_id')->index('item_id');
            $table->float('pos_x')->default(0);
            $table->float('pos_y')->default(0);
            $table->integer('z_index')->default(1);
            $table->float('scale')->default(1);
            $table->boolean('is_key_item')->default(false);
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['outfit_id', 'item_id']);

            $table->foreign('outfit_id', 'Outfit_Items_ibfk_1')
                  ->references('id')->on('Outfits')
                  ->cascadeOnDelete();

            $table->foreign('item_id', 'Outfit_Items_ibfk_2')
                  ->references('id')->on('Clothing_Items')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Outfit_Items');
    }
};