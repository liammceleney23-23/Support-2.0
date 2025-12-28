<?php
// Disable all error output to prevent JSON corruption
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize response
$response = [
    'success' => false,
    'message' => ''
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Validate and sanitize input
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$priority = isset($_POST['priority']) ? sanitize_input($_POST['priority']) : '';
$category = isset($_POST['category']) ? sanitize_input($_POST['category']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Validate required fields
if (empty($name) || empty($email) || empty($priority) || empty($category) || empty($subject) || empty($message)) {
    $response['message'] = 'Please fill in all required fields.';
    echo json_encode($response);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please enter a valid email address.';
    echo json_encode($response);
    exit;
}

// Validate priority
$valid_priorities = ['low', 'medium', 'high', 'critical'];
if (!in_array($priority, $valid_priorities)) {
    $response['message'] = 'Invalid priority level.';
    echo json_encode($response);
    exit;
}

// Load and validate category
$categories_file = 'categories.json';
$valid_categories = [];

if (file_exists($categories_file)) {
    $categories_content = file_get_contents($categories_file);
    $categories = json_decode($categories_content, true);
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            if ($cat['active']) {
                $valid_categories[] = $cat['id'];
            }
        }
    }
}

// Fallback to default categories if none exist
if (empty($valid_categories)) {
    $valid_categories = ['hardware', 'software', 'network', 'security', 'email', 'account', 'other'];
}

if (!in_array($category, $valid_categories)) {
    $response['message'] = 'Invalid category.';
    echo json_encode($response);
    exit;
}

// Generate ticket ID
$ticket_id = 'TICK-' . strtoupper(uniqid());
$timestamp = date('Y-m-d H:i:s');

// Create uploads directory if it doesn't exist
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle file uploads
$attachments = [];
if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip', 'log'];
    $max_file_size = 10 * 1024 * 1024; // 10MB
    $max_files = 5;

    $file_count = count($_FILES['attachments']['name']);

    if ($file_count > $max_files) {
        $response['message'] = "Maximum $max_files files allowed.";
        echo json_encode($response);
        exit;
    }

    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['attachments']['name'][$i];
            $file_size = $_FILES['attachments']['size'][$i];
            $file_tmp = $_FILES['attachments']['tmp_name'][$i];

            // Get file extension
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Validate extension
            if (!in_array($file_ext, $allowed_extensions)) {
                $response['message'] = "File type not allowed: $file_name";
                echo json_encode($response);
                exit;
            }

            // Validate file size
            if ($file_size > $max_file_size) {
                $response['message'] = "File too large: $file_name (max 10MB)";
                echo json_encode($response);
                exit;
            }

            // Create unique filename
            $unique_filename = $ticket_id . '_' . time() . '_' . $i . '.' . $file_ext;
            $destination = $upload_dir . $unique_filename;

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $destination)) {
                $attachments[] = [
                    'original_name' => $file_name,
                    'stored_name' => $unique_filename,
                    'size' => $file_size,
                    'type' => $file_ext,
                    'uploaded_at' => $timestamp
                ];
            } else {
                $response['message'] = "Failed to upload file: $file_name";
                echo json_encode($response);
                exit;
            }
        }
    }
}

// Prepare ticket data
$ticket_data = [
    'ticket_id' => $ticket_id,
    'timestamp' => $timestamp,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'priority' => $priority,
    'category' => $category,
    'subject' => $subject,
    'message' => $message,
    'status' => 'Open',
    'attachments' => $attachments,
    'ip_address' => $_SERVER['REMOTE_ADDR']
];

// Save ticket to file (in production, use database)
$tickets_file = 'tickets.json';
$tickets = [];

// Load existing tickets
if (file_exists($tickets_file)) {
    $tickets_content = file_get_contents($tickets_file);
    $tickets = json_decode($tickets_content, true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
}

// Add new ticket
$tickets[] = $ticket_data;

// Save tickets
if (file_put_contents($tickets_file, json_encode($tickets, JSON_PRETTY_PRINT))) {
    $attachment_count = count($attachments);
    $attachment_msg = $attachment_count > 0 ? " with $attachment_count attachment(s)" : "";

    $response['success'] = true;
    $response['message'] = "Ticket submitted successfully$attachment_msg! Your ticket ID is: $ticket_id. We'll respond within " . getResponseTime($priority) . ".";
    $response['ticket_id'] = $ticket_id;
} else {
    $response['message'] = 'Failed to save ticket. Please try again or contact support directly.';
}

echo json_encode($response);
exit; // Important: exit after sending response

function getResponseTime($priority) {
    switch ($priority) {
        case 'critical':
            return '1 hour';
        case 'high':
            return '4 hours';
        case 'medium':
            return '24 hours';
        case 'low':
            return '48 hours';
        default:
            return '24 hours';
    }
}
?>
