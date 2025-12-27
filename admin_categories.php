<?php
// Admin page for managing ticket categories
// In production, add proper authentication!

$categories_file = 'categories.json';

// Initialize default categories if file doesn't exist
$default_categories = [
    ['id' => 'hardware', 'name' => 'Hardware Issue', 'description' => 'Physical device problems', 'active' => true],
    ['id' => 'software', 'name' => 'Software Problem', 'description' => 'Application and software issues', 'active' => true],
    ['id' => 'network', 'name' => 'Network/Connectivity', 'description' => 'Internet and network problems', 'active' => true],
    ['id' => 'security', 'name' => 'Security Concern', 'description' => 'Security-related issues', 'active' => true],
    ['id' => 'email', 'name' => 'Email Issue', 'description' => 'Email and communication problems', 'active' => true],
    ['id' => 'account', 'name' => 'Account/Password', 'description' => 'Login and account issues', 'active' => true],
    ['id' => 'other', 'name' => 'Other', 'description' => 'Other issues not listed', 'active' => true]
];

if (!file_exists($categories_file)) {
    file_put_contents($categories_file, json_encode($default_categories, JSON_PRETTY_PRINT));
}

// Load categories
$categories = [];
if (file_exists($categories_file)) {
    $categories_content = file_get_contents($categories_file);
    $categories = json_decode($categories_content, true);
    if (!is_array($categories)) {
        $categories = $default_categories;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Zopollo IT Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .admin-header {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px var(--shadow);
        }
        .category-list {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px var(--shadow);
        }
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        .category-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px var(--shadow);
        }
        .category-info {
            flex: 1;
        }
        .category-id {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--accent-primary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .category-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .category-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .category-actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px var(--shadow);
        }
        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: var(--bg-secondary);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 1rem;
        }
        .status-active {
            background: #28a745;
            color: white;
        }
        .status-inactive {
            background: #6c757d;
            color: white;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            padding: 1rem;
        }
        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1999;
        }
        .modal-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 10px 40px var(--shadow);
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            z-index: 2001;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-secondary);
        }
        .modal-header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 2rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: var(--transition);
        }
        .modal-close:hover {
            color: var(--accent-primary);
            transform: rotate(90deg);
        }
        .modal-body {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.875rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }
        .success-message {
            padding: 1rem;
            background: #28a745;
            color: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }
        .error-message {
            padding: 1rem;
            background: #dc3545;
            color: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }
        .back-link:hover {
            background: var(--bg-tertiary);
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Navigation Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <svg width="200" height="60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60">
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#0066ff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#00f0ff;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <g transform="translate(5, 15)">
                        <path d="M 0,0 L 25,0 L 0,30 L 25,30"
                              stroke="url(#logoGradient)"
                              stroke-width="3"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                        <circle cx="0" cy="0" r="2.5" fill="url(#logoGradient)"/>
                        <circle cx="25" cy="0" r="2.5" fill="url(#logoGradient)"/>
                        <circle cx="0" cy="30" r="2.5" fill="url(#logoGradient)"/>
                        <circle cx="25" cy="30" r="2.5" fill="url(#logoGradient)"/>
                        <circle cx="12.5" cy="15" r="3" fill="url(#logoGradient)"/>
                    </g>
                    <text x="40" y="28"
                          font-family="Orbitron, monospace"
                          font-size="20"
                          font-weight="900"
                          fill="url(#logoGradient)">ZOPOLLO</text>
                    <text x="40" y="45"
                          font-family="Orbitron, monospace"
                          font-size="14"
                          font-weight="500"
                          fill="#00f0ff"
                          letter-spacing="2">IT</text>
                </svg>
            </div>
            <button class="close-btn" id="closeBtn" aria-label="Close menu">&times;</button>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="index.php#home" class="nav-link">
                    <span class="nav-icon">🏠</span>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php" class="nav-link">
                    <span class="nav-icon">🎫</span>
                    Submit Ticket
                </a>
            </li>
            <li class="nav-item">
                <a href="view_tickets.php" class="nav-link">
                    <span class="nav-icon">📋</span>
                    View Tickets
                </a>
            </li>
            <li class="nav-item">
                <a href="admin_categories.php" class="nav-link">
                    <span class="nav-icon">⚙️</span>
                    Manage Categories
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php#status" class="nav-link">
                    <span class="nav-icon">📊</span>
                    System Status
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php#knowledge" class="nav-link">
                    <span class="nav-icon">📚</span>
                    Knowledge Base
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php#contact" class="nav-link">
                    <span class="nav-icon">📧</span>
                    Contact Us
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="contact-info">
                <p><strong>24/7 Support Hotline</strong></p>
                <p><a href="tel:+1234567890">+1 (234) 567-890</a></p>
                <p><a href="mailto:support@zopollo.com">support@zopollo.com</a></p>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-logo">
                <button class="hamburger" id="hamburger" aria-label="Open menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <img src="zopollo-logo-compact.svg" alt="Zopollo IT">
            </div>

            <nav class="desktop-nav">
                <a href="index.php#home">🏠 Home</a>
                <a href="index.php">🎫 Submit Ticket</a>
                <a href="view_tickets.php">📋 View Tickets</a>
                <a href="admin_categories.php">⚙️ Categories</a>
                <a href="index.php#status">📊 Status</a>
                <a href="index.php#knowledge">📚 Knowledge</a>
                <a href="index.php#contact">📧 Contact</a>
            </nav>

            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <span id="themeIcon">🌙</span>
                    <span id="themeText">Dark</span>
                </button>
            </div>
        </header>

        <div class="admin-container">
            <a href="view_tickets.php" class="back-link">← Back to Tickets</a>

            <div class="admin-header">
                <h1 style="font-family: 'Orbitron', monospace; font-size: 2rem; margin-bottom: 0.5rem;">Manage Issue Categories</h1>
                <p style="color: var(--text-secondary);">Add, edit, or disable issue categories for ticket submission</p>
            </div>

            <div id="messageContainer"></div>

            <div class="category-list">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-family: 'Orbitron', monospace; font-size: 1.5rem; margin: 0;">Categories</h2>
                    <button class="btn btn-primary" onclick="openAddModal()">+ Add New Category</button>
                </div>

                <?php if (empty($categories)): ?>
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📂</div>
                        <p>No categories yet. Click "Add New Category" to create one.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($categories as $index => $category): ?>
                        <div class="category-item">
                            <div class="category-info">
                                <div class="category-id"><?php echo htmlspecialchars($category['id']); ?></div>
                                <div class="category-name">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                    <span class="status-badge status-<?php echo $category['active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $category['active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="category-description"><?php echo htmlspecialchars($category['description']); ?></div>
                            </div>
                            <div class="category-actions">
                                <button class="btn btn-secondary btn-small" onclick='editCategory(<?php echo json_encode($category); ?>, <?php echo $index; ?>)'>
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-secondary btn-small" onclick="toggleStatus(<?php echo $index; ?>, <?php echo $category['active'] ? 'false' : 'true'; ?>)">
                                    <?php echo $category['active'] ? '🔒 Disable' : '🔓 Enable'; ?>
                                </button>
                                <button class="btn btn-danger btn-small" onclick="deleteCategory(<?php echo $index; ?>, '<?php echo htmlspecialchars($category['name']); ?>')">
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Add/Edit Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-backdrop" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Category</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalMessage"></div>
                <form id="categoryForm">
                    <input type="hidden" id="categoryIndex" name="index">
                    <input type="hidden" id="formAction" name="action" value="add">

                    <div class="form-group">
                        <label for="categoryId">Category ID *</label>
                        <input type="text" id="categoryId" name="id" required
                               placeholder="e.g., printer-issue"
                               pattern="[a-z0-9-]+"
                               title="Lowercase letters, numbers, and hyphens only">
                        <small style="display: block; margin-top: 0.25rem; color: var(--text-secondary); font-size: 0.85rem;">
                            Lowercase letters, numbers, and hyphens only (e.g., printer-issue)
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="categoryName">Category Name *</label>
                        <input type="text" id="categoryName" name="name" required
                               placeholder="e.g., Printer Issue">
                    </div>

                    <div class="form-group">
                        <label for="categoryDescription">Description *</label>
                        <textarea id="categoryDescription" name="description" required
                                  placeholder="Brief description of this category"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="categoryActive" name="active" checked>
                            <label for="categoryActive" style="margin: 0;">Active (available for selection)</label>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">Save Category</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Navigation
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('closeBtn');
        const overlay = document.getElementById('overlay');
        const navLinks = document.querySelectorAll('.nav-link');

        hamburger.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeButton(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);
        });

        function updateThemeButton(theme) {
            if (theme === 'dark') {
                themeIcon.textContent = '☀️';
                themeText.textContent = 'Light';
            } else {
                themeIcon.textContent = '🌙';
                themeText.textContent = 'Dark';
            }
        }

        // Category Management
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Category';
            document.getElementById('categoryForm').reset();
            document.getElementById('formAction').value = 'add';
            document.getElementById('categoryIndex').value = '';
            document.getElementById('categoryId').disabled = false;
            document.getElementById('categoryActive').checked = true;
            document.getElementById('modalMessage').innerHTML = '';
            document.getElementById('categoryModal').classList.add('active');
        }

        function editCategory(category, index) {
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryIndex').value = index;
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryId').disabled = true;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categoryDescription').value = category.description;
            document.getElementById('categoryActive').checked = category.active;
            document.getElementById('modalMessage').innerHTML = '';
            document.getElementById('categoryModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.remove('active');
        }

        async function toggleStatus(index, newStatus) {
            const formData = new FormData();
            formData.append('action', 'toggle');
            formData.append('index', index);
            formData.append('active', newStatus);

            try {
                const response = await fetch('manage_categories.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage(result.message, 'error');
                }
            } catch (error) {
                showMessage('An error occurred. Please try again.', 'error');
            }
        }

        async function deleteCategory(index, name) {
            if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('index', index);

            try {
                const response = await fetch('manage_categories.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage(result.message, 'error');
                }
            } catch (error) {
                showMessage('An error occurred. Please try again.', 'error');
            }
        }

        // Form Submission
        document.getElementById('categoryForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const modalMessage = document.getElementById('modalMessage');

            try {
                const response = await fetch('manage_categories.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                    closeModal();
                } else {
                    modalMessage.innerHTML = '<div class="error-message">' + result.message + '</div>';
                }
            } catch (error) {
                modalMessage.innerHTML = '<div class="error-message">An error occurred. Please try again.</div>';
            }
        });

        function showMessage(message, type) {
            const messageContainer = document.getElementById('messageContainer');
            const className = type === 'success' ? 'success-message' : 'error-message';
            messageContainer.innerHTML = `<div class="${className}">${message}</div>`;
            setTimeout(() => {
                messageContainer.innerHTML = '';
            }, 3000);
        }
    </script>
</body>
</html>
