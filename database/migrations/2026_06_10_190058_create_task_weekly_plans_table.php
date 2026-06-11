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
        Schema::create('task_weekly_plans', function (Blueprint $table) {
            $table->id();
            $table->float('budget');
            $table->float('hours');
            $table->integer('workers_quantity');
            $table->integer('slots');
            $table->timestamp('operation_date');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('extraordinary')->default(0);
            $table->boolean('use_dron')->default(0);
            $table->boolean('prepared_insumos')->default(0);
            $table->foreignId('weekly_plan_id')->constrained()->on('weekly_plans');
            $table->foreignId('tarea_id')->constrained()->on('tareas');
            $table->foreignId('plantation_control_id')->constrained()->on('plantation_controls');
            $table->foreignId('finca_group_id')->constrained()->on('finca_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_weekly_plans');
    }
};
