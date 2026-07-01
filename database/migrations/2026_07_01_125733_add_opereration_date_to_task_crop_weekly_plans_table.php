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
        Schema::table('task_crop_weekly_plans', function (Blueprint $table) {
            $table->timestamp('operation_date')->after('weekly_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_crop_weekly_plans', function (Blueprint $table) {
            $table->dropColumn('operation_date');
        });
    }
};
