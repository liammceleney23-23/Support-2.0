<?php
// Debug script to check file paths and server configuration
header('Content-Type: text/plain');

echo "=== SERVER DIAGNOSTICS ===\n\n";

echo "Current Directory: " . getcwd() . "\n";
echo "Script Directory: " . __DIR__ . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

echo "=== FILE CHECKS ===\n\n";

$files_to_check = [
    'update_ticket.php',
    'manage_ticket.php',
    'submit_ticket.php',
    'check_updates.php',
    'config.php',
    'tickets.json'
];

foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '/' . $file;
    echo "$file:\n";
    echo "  - Exists: " . (file_exists($file) ? 'YES' : 'NO') . "\n";
    echo "  - Full path exists: " . (file_exists($full_path) ? 'YES' : 'NO') . "\n";
    echo "  - Readable: " . (is_readable($file) ? 'YES' : 'NO') . "\n";
    if (file_exists($file)) {
        echo "  - Size: " . filesize($file) . " bytes\n";
        echo "  - Permissions: " . substr(sprintf('%o', fileperms($file)), -4) . "\n";
    }
    echo "\n";
}

echo "=== PHP INFO ===\n\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";

echo "\n=== DIRECTORY LISTING ===\n\n";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo $file . "\n";
    }
}
?>
