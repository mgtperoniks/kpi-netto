<?php
$envPath = __DIR__ . '/.env';
$envContent = file_get_contents($envPath);

// Generate 32 raw bytes natively
$key = 'base64:' . base64_encode(random_bytes(32));

echo "Generated Native Key: " . $key . "\n";

// Remove old app key lines
$newEnvContent = preg_replace('/^APP_KEY=.*$/m', '', $envContent);

// Add the fresh secure one safely to the top
$final = "APP_KEY=" . $key . "\n" . ltrim($newEnvContent);

file_put_contents($envPath, $final);
echo "Written perfectly to .env\n";
