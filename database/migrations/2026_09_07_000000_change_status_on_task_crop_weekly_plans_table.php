<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_crop_weekly_plans', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->change();
        });

        // El cierre ya no calcula los pagos: 3 pasa a significar "cerrada, pendiente
        // de calculo" y 4 "calculada". Las tareas que ya generaron pagos se marcan
        // como calculadas; el resto queda en 3 esperando el paso administrativo.
        DB::table('task_crop_weekly_plans')
            ->where('status', 3)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('employee_payment_weekly_summaries')
                    ->whereColumn('employee_payment_weekly_summaries.task_crop_id', 'task_crop_weekly_plans.id');
            })
            ->update(['status' => 4]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('task_crop_weekly_plans')->where('status', 4)->update(['status' => 3]);

        Schema::table('task_crop_weekly_plans', function (Blueprint $table) {
            $table->boolean('status')->default(1)->change();
        });
    }
};
