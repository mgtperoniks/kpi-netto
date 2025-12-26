<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// MASTER MIRROR
use App\Models\MdOperator;
use App\Models\MdMachine;

class DowntimeController extends Controller
{
    /**
     * ===============================
     * FORM INPUT DOWNTIME
     * ===============================
     */
    public function create()
    {
        return view('downtime.input', [
            'operators' => MdOperator::active()
                ->orderBy('code')
                ->get(),

            'machines'  => MdMachine::active()
                ->orderBy('code')
                ->get(),
        ]);
    }

    /**
     * ===============================
     * SIMPAN DOWNTIME (VERSI SEDERHANA)
     * ===============================
     */
    public function store(Request $request)
    {
        /**
         * 1. VALIDASI INPUT
         */
        $validated = $request->validate([
            'downtime_date'     => 'required|date',
            'operator_code'     => 'required|exists:md_operators,code',
            'machine_code'      => 'required|exists:md_machines,code',
            'duration_minutes'  => 'required|integer|min:1',
            'note'              => 'nullable|string|max:255',
        ]);

        /**
         * 2. SIMPAN KE FACT TABLE
         * Snapshot (NO FK)
         */
        DB::table('downtime_logs')->insert([
            'downtime_date'    => $validated['downtime_date'],
            'operator_code'    => $this->normalizeCode($validated['operator_code']),
            'machine_code'     => $this->normalizeCode($validated['machine_code']),
            'duration_minutes' => $validated['duration_minutes'],
            'note'             => $validated['note'],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()
            ->route('downtime.input')
            ->with('success', 'Downtime berhasil disimpan');
    }

    /**
     * ===============================
     * HELPER NORMALISASI KODE
     * ===============================
     */
    private function normalizeCode(string $value): string
    {
        return strtolower(trim($value));
    }
}
