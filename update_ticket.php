<?php
header('Content-Type: application/json');

$tickets_file = 'tickets.json';
$ticket_id = $_POST['ticket_id'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($ticket_id) || empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Load tickets
$tickets = [];
if (file_exists($tickets_file)) {
    $tickets_content = file_get_contents($tickets_file);
    $tickets = json_decode($tickets_content, true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
}

// Find the ticket
$ticket_index = null;
foreach ($tickets as $index => $ticket) {
    if ($ticket['ticket_id'] === $ticket_id) {
        $ticket_index = $index;
        break;
    }
}

if ($ticket_index === null) {
    echo json_encode(['success' => false, 'message' => 'Ticket not found']);
    exit;
}

// Initialize arrays if they don't exist
if (!isset($tickets[$ticket_index]['responses'])) {
    $tickets[$ticket_index]['responses'] = [];
}
if (!isset($tickets[$ticket_index]['notes'])) {
    $tickets[$ticket_index]['notes'] = [];
}

// Handle different actions
switch ($action) {
    case 'add_response':
        $message = trim($_POST['message'] ?? '');

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Response message cannot be empty']);
            exit;
        }

        $response = [
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message,
            'author' => 'Support Team'
        ];

        $tickets[$ticket_index]['responses'][] = $response;

        // Update ticket status to "In Progress" if it was "Open"
        if ($tickets[$ticket_index]['status'] === 'Open') {
            $tickets[$ticket_index]['status'] = 'Waiting for Customer';
        }

        $success_message = 'Response sent successfully';
        break;

    case 'add_note':
        $content = trim($_POST['content'] ?? '');
        $author = trim($_POST['author'] ?? 'Support Team');

        if (empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Note content cannot be empty']);
            exit;
        }

        $note = [
            'timestamp' => date('Y-m-d H:i:s'),
            'content' => $content,
            'author' => $author
        ];

        $tickets[$ticket_index]['notes'][] = $note;
        $success_message = 'Note added successfully';
        break;

    case 'update_status':
        $status = trim($_POST['status'] ?? '');

        $valid_statuses = ['Open', 'In Progress', 'Waiting for Customer', 'Resolved', 'Closed'];
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit;
        }

        $tickets[$ticket_index]['status'] = $status;
        $success_message = 'Status updated successfully';
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

// Save tickets
if (file_put_contents($tickets_file, json_encode($tickets, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => $success_message
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save ticket data'
    ]);
}
?>
