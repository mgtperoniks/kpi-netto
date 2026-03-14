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
        $newTargets = [
            '403.1.1' => ['Distribusi FL'],
            '403.2.1' => ['Distribusi PF', 'Distribusi Flange Fitting'],
        ];

        foreach ($newTargets as $dept => $processes) {
            foreach ($processes as $process) {
                \Illuminate\Support\Facades\DB::table('process_targets')->insertOrIgnore([
                    'department_code' => $dept,
                    'process_name' => $process,
                    'month' => 3,
                    'year' => 2026,
                    'target_qty' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('process_targets')
            ->where('month', 3)
            ->where('year', 2026)
            ->whereIn('process_name', ['Distribusi FL', 'Distribusi PF', 'Distribusi Flange Fitting'])
            ->delete();
    }
};
