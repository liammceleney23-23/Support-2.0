<?php
header('Content-Type: application/json');

// Get the subscription data from the request
$input = file_get_contents('php://input');
$subscription = json_decode($input, true);

if (!$subscription) {
    echo json_encode(['success' => false, 'message' => 'Invalid subscription data']);
    exit;
}

// Load existing subscriptions
$subscriptions_file = 'subscriptions.json';
$subscriptions = [];

if (file_exists($subscriptions_file)) {
    $subscriptions_content = file_get_contents($subscriptions_file);
    $subscriptions = json_decode($subscriptions_content, true);
    if (!is_array($subscriptions)) {
        $subscriptions = [];
    }
}

// Add timestamp and user identifier
$subscription['created_at'] = date('Y-m-d H:i:s');
$subscription['ip_address'] = $_SERVER['REMOTE_ADDR'];
$subscription['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

// Check if subscription already exists (based on endpoint)
$exists = false;
foreach ($subscriptions as $key => $existing) {
    if (isset($existing['endpoint']) && isset($subscription['endpoint'])
        && $existing['endpoint'] === $subscription['endpoint']) {
        // Update existing subscription
        $subscriptions[$key] = $subscription;
        $exists = true;
        break;
    }
}

// Add new subscription if it doesn't exist
if (!$exists) {
    $subscriptions[] = $subscription;
}

// Save subscriptions
if (file_put_contents($subscriptions_file, json_encode($subscriptions, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Subscription saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save subscription']);
}
?>
