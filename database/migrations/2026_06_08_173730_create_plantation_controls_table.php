<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plantation_controls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->float('total_plants');
            $table->foreignId('lote_id')->constrained()->on('lotes');
            $table->foreignId('recipe_id')->constrained()->on('recipes');
            $table->foreignId('crop_id')->constrained()->on('crops');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantation_controls');
    }
};
