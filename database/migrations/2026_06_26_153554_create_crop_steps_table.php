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
        Schema::create('crop_steps', function (Blueprint $table) {
            $table->id();
            $table->integer('step_order');
            $table->string('target');
            $table->string('operation');
            $table->string('left');
            $table->string('right');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_steps');
    }
};
