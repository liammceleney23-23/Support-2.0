<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (disable in production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

// Validate category
$valid_categories = ['hardware', 'software', 'network', 'security', 'email', 'account', 'other'];
if (!in_array($category, $valid_categories)) {
    $response['message'] = 'Invalid category.';
    echo json_encode($response);
    exit;
}

// Generate ticket ID
$ticket_id = 'TICK-' . strtoupper(uniqid());
$timestamp = date('Y-m-d H:i:s');

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
    // Send email notification (optional - configure SMTP settings)
    $to = 'support@zopollo.com'; // Change to your support email
    $email_subject = "New Support Ticket: $ticket_id - $subject";
    $email_message = "
New Support Ticket Received

Ticket ID: $ticket_id
Timestamp: $timestamp
Priority: " . strtoupper($priority) . "
Category: " . ucfirst($category) . "

Customer Information:
Name: $name
Email: $email
Phone: $phone

Subject: $subject

Message:
$message

---
This is an automated notification from Zopollo IT Support System.
    ";

    $email_headers = "From: noreply@zopollo.com\r\n";
    $email_headers .= "Reply-To: $email\r\n";
    $email_headers .= "X-Mailer: PHP/" . phpversion();

    // Uncomment to enable email notifications (requires mail server configuration)
    // mail($to, $email_subject, $email_message, $email_headers);

    // Send confirmation email to customer
    $customer_subject = "Ticket Confirmation: $ticket_id";
    $customer_message = "
Dear $name,

Thank you for contacting Zopollo IT Support. Your support ticket has been received and assigned the following ID: $ticket_id

Ticket Details:
Priority: " . strtoupper($priority) . "
Category: " . ucfirst($category) . "
Subject: $subject

Our team will review your request and respond as soon as possible based on the priority level.

Priority Response Times:
- Critical: Within 1 hour
- High: Within 4 hours
- Medium: Within 24 hours
- Low: Within 48 hours

You can reference ticket ID $ticket_id when following up on this request.

Best regards,
Zopollo IT Support Team
24/7 Support: +1 (234) 567-890
Email: support@zopollo.com
    ";

    $customer_headers = "From: support@zopollo.com\r\n";
    $customer_headers .= "Reply-To: support@zopollo.com\r\n";
    $customer_headers .= "X-Mailer: PHP/" . phpversion();

    // Uncomment to enable customer confirmation emails
    // mail($email, $customer_subject, $customer_message, $customer_headers);

    $response['success'] = true;
    $response['message'] = "Ticket submitted successfully! Your ticket ID is: $ticket_id. We'll respond within " . getResponseTime($priority) . ".";
    $response['ticket_id'] = $ticket_id;
} else {
    $response['message'] = 'Failed to save ticket. Please try again or contact support directly.';
}

echo json_encode($response);

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
