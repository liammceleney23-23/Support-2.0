<?php
header('Content-Type: application/json');

$categories_file = 'categories.json';
$action = $_POST['action'] ?? '';

if (empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Missing action']);
    exit;
}

// Load categories
$categories = [];
if (file_exists($categories_file)) {
    $categories_content = file_get_contents($categories_file);
    $categories = json_decode($categories_content, true);
    if (!is_array($categories)) {
        $categories = [];
    }
}

// Handle different actions
switch ($action) {
    case 'add':
        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $active = isset($_POST['active']) && $_POST['active'] === 'on';

        // Validate required fields
        if (empty($id) || empty($name) || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Validate ID format (lowercase, numbers, hyphens only)
        if (!preg_match('/^[a-z0-9-]+$/', $id)) {
            echo json_encode(['success' => false, 'message' => 'Category ID must contain only lowercase letters, numbers, and hyphens']);
            exit;
        }

        // Check if ID already exists
        foreach ($categories as $category) {
            if ($category['id'] === $id) {
                echo json_encode(['success' => false, 'message' => 'Category ID already exists']);
                exit;
            }
        }

        // Add new category
        $categories[] = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'active' => $active
        ];

        $success_message = 'Category added successfully';
        break;

    case 'edit':
        $index = $_POST['index'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $active = isset($_POST['active']) && $_POST['active'] === 'on';

        if ($index === '' || !isset($categories[$index])) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        // Validate required fields
        if (empty($name) || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }

        // Update category (keep the same ID)
        $categories[$index]['name'] = $name;
        $categories[$index]['description'] = $description;
        $categories[$index]['active'] = $active;

        $success_message = 'Category updated successfully';
        break;

    case 'toggle':
        $index = $_POST['index'] ?? '';
        $active = $_POST['active'] === 'true';

        if ($index === '' || !isset($categories[$index])) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        $categories[$index]['active'] = $active;
        $success_message = 'Category status updated successfully';
        break;

    case 'delete':
        $index = $_POST['index'] ?? '';

        if ($index === '' || !isset($categories[$index])) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        // Remove category
        array_splice($categories, $index, 1);
        $success_message = 'Category deleted successfully';
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

// Save categories
if (file_put_contents($categories_file, json_encode($categories, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => $success_message
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save categories'
    ]);
}
?>
