<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ProcessTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Netto Flange (403.1.1)
        $flangeProcesses = [
            'HAND DRILL FL',
            'BLASTING FL',
            'POTONG FL',
            'GERINDA KASAR FL',
            'LAS ARGON FL',
        ];

        // Netto Fitting (403.2.1)
        $fittingProcesses = [
            'GERINDA HALUS PF',
            'GERINDA KASAR PF',
            'POTONG PF',
            'GERINDA FINISH PF',
            'GERINDA FLANGE PF',
            'HAND DRILL PF',
            'BOR FITTING PF',
            'BLASTING PF',
            'GERINDA FLAP PF',
        ];

        // Insert unique processes for Flange (403.1.1)
        foreach ($flangeProcesses as $process) {
            DB::table('process_targets')->updateOrInsert(
                [
                    'department_code' => '403.1.1',
                    'process_name' => $process,
                    'month' => date('n'),
                    'year' => date('Y')
                ],
                ['target_qty' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Insert unique processes for Fitting (403.2.1)
        foreach ($fittingProcesses as $process) {
            DB::table('process_targets')->updateOrInsert(
                [
                    'department_code' => '403.2.1',
                    'process_name' => $process,
                    'month' => date('n'),
                    'year' => date('Y')
                ],
                ['target_qty' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
