<?php
// Diagnostic: show the first 128 bytes of signup.php to detect stray characters or BOM
$file = __DIR__ . '/signup.php';
$contents = file_get_contents($file);
$first = substr($contents, 0, 128);
// show hex and raw
$hex = implode(' ', array_map(function($c){ return sprintf('%02x', ord($c)); }, str_split($first)));
$visible = htmlspecialchars($first);
header('Content-Type: text/plain; charset=utf-8');
echo "File: $file\n\n";
echo "First 128 bytes (hex):\n" . $hex . "\n\n";
echo "First 128 bytes (as text):\n" . $visible . "\n";
?>