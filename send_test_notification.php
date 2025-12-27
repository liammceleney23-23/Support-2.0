<?php
/**
 * Test script to send push notifications
 * This demonstrates how to trigger notifications for testing purposes
 *
 * Usage: Call this script with ticket_id parameter to send a test notification
 * Example: send_test_notification.php?ticket_id=TICK-123456
 */

header('Content-Type: application/json');

// Get ticket ID from query parameter
$ticket_id = $_GET['ticket_id'] ?? 'TEST-' . strtoupper(uniqid());

// Load subscriptions
$subscriptions_file = 'subscriptions.json';
if (!file_exists($subscriptions_file)) {
    echo json_encode([
        'success' => false,
        'message' => 'No subscriptions found. Users need to grant notification permission first.'
    ]);
    exit;
}

$subscriptions_content = file_get_contents($subscriptions_file);
$subscriptions = json_decode($subscriptions_content, true);

if (!is_array($subscriptions) || empty($subscriptions)) {
    echo json_encode([
        'success' => false,
        'message' => 'No active subscriptions found.'
    ]);
    exit;
}

// Create test notification payload
$payload = json_encode([
    'title' => 'IT Support Ticket Update',
    'body' => "Test notification for ticket $ticket_id",
    'ticket_id' => $ticket_id,
    'url' => "/manage_ticket.php?id=$ticket_id"
]);

// Log the notification attempt
$log_file = 'notifications.log';
$log_entry = date('Y-m-d H:i:s') . " - Test notification queued for $ticket_id\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

echo json_encode([
    'success' => true,
    'message' => "Test notification queued for $ticket_id",
    'subscription_count' => count($subscriptions),
    'note' => 'To send actual push notifications, you need to install the web-push PHP library and configure VAPID keys. See update_ticket.php for implementation details.'
]);

/**
 * PRODUCTION IMPLEMENTATION NOTES:
 *
 * To enable actual push notification delivery, you need to:
 *
 * 1. Install the web-push library:
 *    composer require minishlink/web-push
 *
 * 2. Generate VAPID keys:
 *    vendor/bin/web-push generate-vapid-keys
 *
 * 3. Store keys securely (e.g., in environment variables or config file)
 *
 * 4. Update the applicationServerKey in JavaScript files with your VAPID public key
 *
 * 5. Use the web-push library to send notifications:
 *
 *    require_once 'vendor/autoload.php';
 *    use Minishlink\WebPush\WebPush;
 *    use Minishlink\WebPush\Subscription;
 *
 *    $auth = [
 *        'VAPID' => [
 *            'subject' => 'mailto:support@zopollo.com',
 *            'publicKey' => 'YOUR_PUBLIC_VAPID_KEY',
 *            'privateKey' => 'YOUR_PRIVATE_VAPID_KEY'
 *        ]
 *    ];
 *
 *    $webPush = new WebPush($auth);
 *
 *    foreach ($subscriptions as $subscription) {
 *        $webPush->queueNotification(
 *            Subscription::create($subscription),
 *            $payload
 *        );
 *    }
 *
 *    foreach ($webPush->flush() as $report) {
 *        $endpoint = $report->getRequest()->getUri()->__toString();
 *        if ($report->isSuccess()) {
 *            echo "Message sent successfully to {$endpoint}\n";
 *        } else {
 *            echo "Message failed to sent to {$endpoint}: {$report->getReason()}\n";
 *        }
 *    }
 */
?>
