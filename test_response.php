<?php
// Simple test to check if JSON is working
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Test response working',
    'timestamp' => date('Y-m-d H:i:s')
]);
exit;
?>
