<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\MdHeatNumberMirror;
use Carbon\Carbon;

class PullMasterHeatNumbers extends Command
{
    protected $signature = 'pull:master-heat-numbers';
    protected $description = 'Pull Heat Numbers from masterdatakpi source';

    public function handle()
    {
        $this->info('Starting Heat Number synchronization...');
        $count = 0;

        // Ambil data dari koneksi 'master'
        DB::connection('master')
            ->table('md_heat_numbers')
            ->where('status', 'active')
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$count) {
                foreach ($rows as $row) {
                    $mirror = MdHeatNumberMirror::withoutGlobalScopes()->where('heat_number', $row->heat_number)
                        ->where('item_code', $row->item_code)
                        ->first();

                    // Compare source_updated_at to avoid unnecessary writes
                    if ($mirror && $mirror->source_updated_at && Carbon::parse($mirror->source_updated_at)->equalTo(Carbon::parse($row->updated_at))) {
                        continue;
                    }

                    MdHeatNumberMirror::withoutGlobalScopes()->updateOrCreate(
                        [
                            'heat_number' => trim($row->heat_number),
                            'item_code' => trim($row->item_code)
                        ],
                        [
                            'kode_produksi' => trim($row->kode_produksi),
                            'item_name' => trim($row->item_name),
                            'size' => trim($row->size),
                            'customer' => trim($row->customer),
                            'line' => trim($row->line),
                            'cor_qty' => $row->cor_qty,
                            'status' => $row->status,
                            'source_updated_at' => $row->updated_at,
                            'last_sync_at' => now(),
                        ]
                    );
                    $count++;
                }
            });

        $this->info("Heat Number synchronization finished. Total updated: {$count}");
    }
}
