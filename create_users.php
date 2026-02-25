<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
try {
    User::updateOrCreate(
        ['email' => 'adminhr@peroniks.com'],
        [
            'name' => 'Admin HR',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_code' => '400', // Assumed from context
            'tim' => null
        ]
    );
    User::updateOrCreate(
        ['email' => 'managerhr@peroniks.com'],
        [
            'name' => 'Manager HR',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'department_code' => '400',
            'tim' => null
        ]
    );
    echo "Users successfully created/updated.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
