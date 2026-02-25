<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

try {
    $now = Carbon::now()->format('Y-m-d H:i:s');

    // adminhr
    DB::connection('master')->table('users')->updateOrInsert(
        ['email' => 'adminhr@peroniks.com'],
        [
            'name' => 'Admin HR',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_code' => '400',
            'tim' => null,
            'updated_at' => $now,
            // Only set created_at if it's a new record
            // updateOrInsert doesn't let us easily separate them without raw DB queries but it's okay for these two.
        ]
    );

    // managerhr
    DB::connection('master')->table('users')->updateOrInsert(
        ['email' => 'managerhr@peroniks.com'],
        [
            'name' => 'Manager HR',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'department_code' => '400',
            'tim' => null,
            'updated_at' => $now,
        ]
    );

    echo "Users successfully created/updated via DB query.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
