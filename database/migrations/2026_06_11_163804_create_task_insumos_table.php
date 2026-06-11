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
        Schema::create('task_insumos', function (Blueprint $table) {
            $table->id();
            $table->float('assigned_quantity');
            $table->float('used_quantity')->nullable(true);
            $table->foreignId('task_weekly_plan_id')->constrained()->on('task_weekly_plans');
            $table->foreignId('insumo_id')->constrained()->on('insumos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_insumos');
    }
};
