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

        // Handle file uploads
        $attachments = [];
        $upload_dir = 'uploads/';

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip', 'log'];
            $max_file_size = 10 * 1024 * 1024; // 10MB
            $max_files = 5;

            $file_count = count($_FILES['attachments']['name']);

            if ($file_count > $max_files) {
                echo json_encode(['success' => false, 'message' => "Maximum $max_files files allowed"]);
                exit;
            }

            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                    $file_name = $_FILES['attachments']['name'][$i];
                    $file_size = $_FILES['attachments']['size'][$i];
                    $file_tmp = $_FILES['attachments']['tmp_name'][$i];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if (!in_array($file_ext, $allowed_extensions)) {
                        echo json_encode(['success' => false, 'message' => "File type not allowed: $file_name"]);
                        exit;
                    }

                    if ($file_size > $max_file_size) {
                        echo json_encode(['success' => false, 'message' => "File too large: $file_name (max 10MB)"]);
                        exit;
                    }

                    $unique_filename = $ticket_id . '_response_' . time() . '_' . $i . '.' . $file_ext;
                    $destination = $upload_dir . $unique_filename;

                    if (move_uploaded_file($file_tmp, $destination)) {
                        $attachments[] = [
                            'original_name' => $file_name,
                            'stored_name' => $unique_filename,
                            'size' => $file_size,
                            'type' => $file_ext,
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        echo json_encode(['success' => false, 'message' => "Failed to upload file: $file_name"]);
                        exit;
                    }
                }
            }
        }

        $response = [
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message,
            'author' => 'Support Team',
            'attachments' => $attachments
        ];

        $tickets[$ticket_index]['responses'][] = $response;

        // Update ticket status to "In Progress" if it was "Open"
        if ($tickets[$ticket_index]['status'] === 'Open') {
            $tickets[$ticket_index]['status'] = 'Waiting for Customer';
        }

        $attachment_msg = count($attachments) > 0 ? ' with ' . count($attachments) . ' attachment(s)' : '';
        $success_message = 'Response sent successfully' . $attachment_msg;
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
