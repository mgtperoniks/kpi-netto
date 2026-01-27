<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;
use App\Models\MdOperatorMirror;
use Barryvdh\DomPDF\Facade\Pdf;

class DailyReportController extends Controller
{
    /**
     * ===============================
     * INDEX (LIST TANGGAL)
     * ===============================
     */
    public function operatorIndex()
    {
        // Ambil summary per tanggal
        $dates = ProductionLog::selectRaw('
                production_date, 
                SUM(actual_qty) as total_qty, 
                AVG(achievement_percent) as avg_kpi,
                COUNT(*) as total_logs
            ')
            ->groupBy('production_date')
            ->orderBy('production_date', 'desc')
            ->get();

        return view('daily_report.operator.index', [
            'dates' => $dates,
        ]);
    }

    /**
     * ===============================
     * SHOW (DETAIL HARIAN)
     * ===============================
     */
    public function operatorShow($date)
    {
        // Ambil data detail per baris log (bukan summary)
        $rows = ProductionLog::with(['operator', 'machine', 'item'])
            ->where('production_date', $date)
            ->orderBy('shift')
            ->orderBy('operator_code')
            ->orderBy('time_start')
            ->get();

        return view('daily_report.operator.show', [
            'rows' => $rows,
            'date' => $date,
        ]);
    }

    /**
     * ===============================
     * DESTROY (HAPUS INPUTAN)
     * ===============================
     */
    public function operatorDestroy($id)
    {
        $log = ProductionLog::findOrFail($id);

        // Simpan info untuk flash message
        $info = "Inputan Operator {$log->operator_code} di Mesin {$log->machine_code}";

        $log->delete();

        return redirect()
            ->back()
            ->with('success', "Data berhasil dihapus: $info");
    }

    /**
     * ===============================
     * EXPORT PDF (PORTRAIT)
     * ===============================
     */
    public function operatorExportPdf($date)
    {
        $rows = ProductionLog::with(['operator', 'machine', 'item'])
            ->where('production_date', $date)
            ->orderBy('shift')
            ->orderBy('operator_code')
            ->orderBy('time_start')
            ->get();

        $pdf = Pdf::loadView('daily_report.operator.pdf', [
            'rows' => $rows,
            'date' => $date,
        ]);

        // Portrait orientation as requested
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan-Harian-Operator-{$date}.pdf");
    }
}
