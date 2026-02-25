<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_targets', function (Blueprint $table) {
            $table->id();
            $table->string('department_code', 50);
            $table->string('process_name');
            $table->integer('month')->default(date('n'));
            $table->integer('year')->default(date('Y'));
            $table->integer('target_qty')->default(0);
            $table->timestamps();

            // Limit each department to have unique process names per month and year
            $table->unique(['department_code', 'process_name', 'month', 'year'], 'proc_tgt_dept_proc_mo_yr_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_targets');
    }
};
