<?php
header('Content-Type: application/json');

// Get the last check timestamp from the client
$last_check = $_GET['last_check'] ?? '';
$user_email = $_GET['email'] ?? '';

if (empty($last_check)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing last_check parameter'
    ]);
    exit;
}

// Load tickets
$tickets_file = 'tickets.json';
$updates = [];

if (file_exists($tickets_file)) {
    $tickets_content = file_get_contents($tickets_file);
    $tickets = json_decode($tickets_content, true);

    if (is_array($tickets)) {
        foreach ($tickets as $ticket) {
            // Check if this ticket belongs to the user (if email provided)
            if (!empty($user_email) && $ticket['email'] !== $user_email) {
                continue;
            }

            // Check for new responses after last check
            if (isset($ticket['responses']) && is_array($ticket['responses'])) {
                foreach ($ticket['responses'] as $response) {
                    if (isset($response['timestamp']) && strtotime($response['timestamp']) > strtotime($last_check)) {
                        $updates[] = [
                            'type' => 'new_response',
                            'ticket_id' => $ticket['ticket_id'],
                            'subject' => $ticket['subject'],
                            'timestamp' => $response['timestamp'],
                            'message' => 'New response added to your ticket',
                            'url' => "manage_ticket.php?id=" . $ticket['ticket_id']
                        ];
                        break; // Only notify once per ticket
                    }
                }
            }

            // Check if ticket status changed recently
            if (isset($ticket['status_updated_at']) && strtotime($ticket['status_updated_at']) > strtotime($last_check)) {
                $updates[] = [
                    'type' => 'status_change',
                    'ticket_id' => $ticket['ticket_id'],
                    'subject' => $ticket['subject'],
                    'status' => $ticket['status'],
                    'timestamp' => $ticket['status_updated_at'],
                    'message' => 'Ticket status changed to: ' . $ticket['status'],
                    'url' => "manage_ticket.php?id=" . $ticket['ticket_id']
                ];
            }
        }
    }
}

echo json_encode([
    'success' => true,
    'has_updates' => !empty($updates),
    'update_count' => count($updates),
    'updates' => $updates,
    'server_time' => date('Y-m-d H:i:s')
]);
?>
