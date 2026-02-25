<?php

$logs = \App\Models\ProductionLog::whereNotNull('department_code')->get();
$count = 0;
$targets = ['hand drill', 'blasting', 'potong', 'gerinda kasar', 'las argon', 'gerinda halus', 'gerinda finish', 'gerinda flange', 'bor fitting', 'gerinda flap'];

foreach ($logs as $log) {
    if (in_array(strtolower($log->item_code), $targets)) {
        if ($log->department_code == '403.1.1') {
            $log->item_code = $log->item_code . ' FL';
            $log->save();
            $count++;
        } elseif ($log->department_code == '403.2.1') {
            if (strtolower($log->item_code) === 'bor fitting') {
                // skip bor fitting
            } else {
                $log->item_code = $log->item_code . ' PF';
                $log->save();
                $count++;
            }
        }
    }
}
echo "Updated $count records.\n";
