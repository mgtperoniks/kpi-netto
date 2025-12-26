<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;

// MASTER MIRROR (READ ONLY)
use App\Models\MdOperator;
use App\Models\MdMachine;
use App\Models\MdItem;

class ProductionController extends Controller
{
    /**
     * =================================
     * FORM INPUT PRODUKSI
     * =================================
     */
    public function create()
    {
        return view('production.input', [
            'operators' => MdOperator::active()->orderBy('name')->get(),
            'machines'  => MdMachine::active()->orderBy('name')->get(),
            'items'     => MdItem::active()
                ->orderBy('code')
                ->get(['code', 'name', 'cycle_time_sec']),
        ]);
    }

    /**
     * =================================
     * SIMPAN DATA PRODUKSI (AMAN)
     * =================================
     */
    public function store(Request $request)
    {
        /**
         * 1. VALIDASI INPUT
         * Server adalah source of truth
         */
        $validated = $request->validate([
            'production_date' => 'required|date',
            'shift'           => 'required|string|max:10',

            'operator_code'   => 'required|string',
            'machine_code'    => 'required|string',

            // VALIDASI KE MASTER MIRROR
            'item_code'       => 'required|exists:md_items_mirror,code',

            'time_start'      => 'required|date_format:H:i',
            'time_end'        => 'required|date_format:H:i|after:time_start',

            'actual_qty'      => 'required|integer|min:0',
        ]);

        /**
         * 2. AMBIL MASTER ITEM (FAIL FAST)
         * Cycle time WAJIB dari server
         */
        $item = MdItem::active()
            ->where('code', $validated['item_code'])
            ->firstOrFail();

        /**
         * 3. HITUNG DURASI KERJA
         */
        $workSeconds = strtotime($validated['time_end'])
            - strtotime($validated['time_start']);

        if ($workSeconds <= 0) {
            return back()
                ->withErrors(['time_end' => 'Jam selesai harus lebih besar dari jam mulai'])
                ->withInput();
        }

        $workHours = round($workSeconds / 3600, 2);

        /**
         * 4. HITUNG TARGET PRODUKSI
         */
        $cycleTimeSec = (int) $item->cycle_time_sec;

        $targetQty = $cycleTimeSec > 0
            ? intdiv($workSeconds, $cycleTimeSec)
            : 0;

        /**
         * 5. HITUNG ACHIEVEMENT
         */
        $actualQty = (int) $validated['actual_qty'];

        $achievementPercent = $targetQty > 0
            ? round(($actualQty / $targetQty) * 100, 2)
            : 0;

        /**
         * 6. SIMPAN KE FACT TABLE
         * Snapshot data (NO FK)
         */
        ProductionLog::create([
            'production_date'     => $validated['production_date'],
            'shift'               => $validated['shift'],

            'operator_code'       => $this->normalizeCode($validated['operator_code']),
            'machine_code'        => $this->normalizeCode($validated['machine_code']),
            'item_code'           => $this->normalizeCode($validated['item_code']),

            'time_start'          => $validated['time_start'],
            'time_end'            => $validated['time_end'],
            'work_hours'          => $workHours,

            // nilai kritis (snapshot)
            'cycle_time_used_sec' => $cycleTimeSec,
            'target_qty'          => $targetQty,
            'actual_qty'          => $actualQty,
            'achievement_percent' => $achievementPercent,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data produksi berhasil disimpan');
    }

    /**
     * =================================
     * HELPER NORMALISASI KODE
     * =================================
     */
    private function normalizeCode(string $value): string
    {
        return strtolower(trim($value));
    }
}
