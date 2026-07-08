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
        Schema::create('employee_payment_weekly_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->float('hours');
            $table->float('amount');
            $table->timestamp('date');
            $table->float('theorical_hours');
            $table->foreignId('task_plan_id')->nullable()->constrained()->on('task_weekly_plans');
            $table->foreignId('task_crop_id')->nullable()->constrained()->on('task_crop_weekly_plans');
            $table->foreignId('weekly_plan_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payment_weekly_summaries');
    }
};
