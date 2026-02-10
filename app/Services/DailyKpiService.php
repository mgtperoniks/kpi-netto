<?php

namespace App\Services;

use App\Models\ProductionLog;
use App\Models\DailyKpiOperator;
use App\Models\DailyKpiMachine;
use Illuminate\Support\Facades\DB;

class DailyKpiService
{
    /**
     * Generate KPI harian per Operator
     */
    public static function generateOperatorDaily(string $date): void
    {
        $rows = ProductionLog::withoutGlobalScopes()
            ->where('production_date', $date)
            ->select(
                'department_code',
                'operator_code',
                DB::raw('SUM(work_hours) as total_work_hours'),
                DB::raw('SUM(target_qty) as total_target_qty'),
                DB::raw('SUM(actual_qty) as total_actual_qty')
            )
            ->groupBy('department_code', 'operator_code')
            ->get();

        // 1. Update or Create for active operators
        $activeOperators = [];
        foreach ($rows as $row) {
            $activeOperators[] = $row->operator_code;

            $kpiPercent = $row->total_target_qty > 0
                ? round(($row->total_actual_qty / $row->total_target_qty) * 100, 2)
                : 0;

            DailyKpiOperator::withoutGlobalScopes()->updateOrCreate(
                [
                    'kpi_date' => $date,
                    'department_code' => $row->department_code,
                    'operator_code' => $row->operator_code,
                ],
                [
                    'total_work_hours' => $row->total_work_hours,
                    'total_target_qty' => $row->total_target_qty,
                    'total_actual_qty' => $row->total_actual_qty,
                    'kpi_percent' => $kpiPercent,
                ]
            );
        }

        // 2. Remove Stale Records (Operators that no longer have logs for this date)
        DailyKpiOperator::withoutGlobalScopes()
            ->where('kpi_date', $date)
            ->whereNotIn('operator_code', $activeOperators)
            ->delete();
    }

    /**
     * Generate KPI harian per Mesin
     */
    public static function generateMachineDaily(string $date): void
    {
        $rows = ProductionLog::withoutGlobalScopes()
            ->where('production_date', $date)
            ->select(
                'department_code',
                'machine_code',
                DB::raw('SUM(work_hours) as total_work_hours'),
                DB::raw('SUM(target_qty) as total_target_qty'),
                DB::raw('SUM(actual_qty) as total_actual_qty')
            )
            ->groupBy('department_code', 'machine_code')
            ->get();

        // 1. Update active machines
        $activeMachines = [];
        foreach ($rows as $row) {
            $activeMachines[] = $row->machine_code;

            $kpiPercent = $row->total_target_qty > 0
                ? round(($row->total_actual_qty / $row->total_target_qty) * 100, 2)
                : 0;

            DailyKpiMachine::withoutGlobalScopes()->updateOrCreate(
                [
                    'kpi_date' => $date,
                    'department_code' => $row->department_code,
                    'machine_code' => $row->machine_code,
                ],
                [
                    'total_work_hours' => $row->total_work_hours,
                    'total_target_qty' => $row->total_target_qty,
                    'total_actual_qty' => $row->total_actual_qty,
                    'kpi_percent' => $kpiPercent,
                ]
            );
        }

        // 2. Remove Stale Records
        DailyKpiMachine::withoutGlobalScopes()
            ->where('kpi_date', $date)
            ->whereNotIn('machine_code', $activeMachines)
            ->delete();
    }
}

