<?php
$raw = file_get_contents('deploy_key.txt');
$clean = str_replace("\x00", "", $raw);
$clean = str_replace("\xFF\xFE", "", $clean);
$clean = trim($clean);

$chunks = str_split($clean, 20);
foreach ($chunks as $chunk) {
    echo $chunk . "\n";
}
