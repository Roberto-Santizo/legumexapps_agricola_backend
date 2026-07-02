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
        Schema::create('task_crop_weekly_plan_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_crop_weekly_plan_id')->constrained()->on('task_crop_weekly_plans');
            $table->foreignId('crop_input_id')->constrained();
            $table->float('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_crop_weekly_plan_inputs');
    }
};
