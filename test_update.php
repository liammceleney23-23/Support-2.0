<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Simple test to verify POST requests work
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode([
        'success' => true,
        'message' => 'POST request received successfully',
        'received_data' => $_POST,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'This endpoint requires POST method',
        'current_method' => $_SERVER['REQUEST_METHOD']
    ]);
}
exit;
?>
