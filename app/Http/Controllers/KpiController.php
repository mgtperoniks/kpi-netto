<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\KpiBubutService;

class KpiController extends Controller
{
    /**
     * ===============================
     * KPI PER SHIFT (BUBUT)
     * ===============================
     */
    public function shift(Request $request, KpiBubutService $service)
    {
        /**
         * Ambil parameter (default aman)
         */
        $date  = $request->input('date', now()->toDateString());
        $shift = $request->input('shift', 'A');

        /**
         * Ambil data produksi
         */
        $productions = DB::table('production_logs')
            ->where('production_date', $date)
            ->where('shift', $shift)
            ->get();

        /**
         * Ambil data downtime
         */
        $downtimes = DB::table('downtime_logs')
            ->where('production_date', $date)
            ->where('shift', $shift)
            ->get();

        /**
         * Hitung KPI (server-side authority)
         */
        $result = $service->calculateWithDowntime(
            $productions,
            $downtimes
        );

        return view('kpi.shift', compact(
            'date',
            'shift',
            'result'
        ));
    }
}
