<?php
// Ticket management page
// In production, add proper authentication!

$tickets_file = 'tickets.json';
$ticket_id = $_GET['id'] ?? '';
$ticket = null;
$tickets = [];

if (file_exists($tickets_file)) {
    $tickets_content = file_get_contents($tickets_file);
    $tickets = json_decode($tickets_content, true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
}

// Find the specific ticket
foreach ($tickets as $t) {
    if ($t['ticket_id'] === $ticket_id) {
        $ticket = $t;
        break;
    }
}

if (!$ticket) {
    header('Location: view_tickets.php');
    exit;
}

// Initialize responses and notes if they don't exist
if (!isset($ticket['responses'])) {
    $ticket['responses'] = [];
}
if (!isset($ticket['notes'])) {
    $ticket['notes'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ticket <?php echo htmlspecialchars($ticket_id); ?> - Zopollo IT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

    <!-- In-App Notifications -->
    <script src="in-app-notifications.js"></script>

    <style>
        .ticket-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .back-btn {
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
        .back-btn:hover {
            background: var(--bg-tertiary);
            transform: translateX(-5px);
        }
        .ticket-header-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px var(--shadow);
        }
        .ticket-id {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--accent-primary);
            font-size: 1.5rem;
        }
        .priority-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .priority-critical { background: #dc3545; color: white; }
        .priority-high { background: #fd7e14; color: white; }
        .priority-medium { background: #ffc107; color: #000; }
        .priority-low { background: #28a745; color: white; }
        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            background: var(--accent-primary);
            color: white;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .info-item {
            padding: 1rem;
            background: var(--bg-tertiary);
            border-radius: 8px;
        }
        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: var(--text-primary);
            font-size: 1rem;
        }
        .content-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px var(--shadow);
        }
        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        .message-box {
            background: var(--bg-tertiary);
            padding: 1.5rem;
            border-radius: 8px;
            line-height: 1.6;
            border-left: 4px solid var(--accent-primary);
        }
        .response-item, .note-item {
            background: var(--bg-tertiary);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-secondary);
        }
        .response-header, .note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .response-content, .note-content {
            line-height: 1.6;
            color: var(--text-primary);
        }
        .action-form {
            background: var(--bg-tertiary);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        .form-group textarea {
            width: 100%;
            padding: 0.875rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
        }
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }
        .form-group select {
            width: 100%;
            padding: 0.875rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
        }
        .btn {
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
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
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: var(--bg-tertiary);
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
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        @media (max-width: 968px) {
            .two-col {
                grid-template-columns: 1fr;
            }
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
                <a href="index.php" class="nav-link" id="openTicketModal">
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
                <a href="index.php" id="openTicketModalDesktop">🎫 Submit Ticket</a>
                <a href="view_tickets.php">📋 View Tickets</a>
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

        <div class="ticket-container">
            <a href="view_tickets.php" class="back-btn">← Back to All Tickets</a>

            <!-- Ticket Header -->
            <div class="ticket-header-card">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <div class="ticket-id"><?php echo htmlspecialchars($ticket['ticket_id']); ?></div>
                        <h1 style="font-size: 1.75rem; margin-top: 0.5rem; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($ticket['subject']); ?>
                        </h1>
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <span class="priority-badge priority-<?php echo htmlspecialchars($ticket['priority']); ?>">
                                <?php echo htmlspecialchars($ticket['priority']); ?>
                            </span>
                            <span class="status-badge">
                                <?php echo htmlspecialchars($ticket['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right; color: var(--text-secondary);">
                        <div style="font-size: 0.85rem;">Submitted</div>
                        <div style="font-weight: 600; margin-top: 0.25rem;">
                            <?php echo htmlspecialchars($ticket['timestamp']); ?>
                        </div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Contact Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($ticket['name']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:<?php echo htmlspecialchars($ticket['email']); ?>"
                               style="color: var(--accent-primary); text-decoration: none;">
                                <?php echo htmlspecialchars($ticket['email']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">
                            <?php echo !empty($ticket['phone']) ? htmlspecialchars($ticket['phone']) : 'N/A'; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Category</div>
                        <div class="info-value"><?php echo ucfirst(htmlspecialchars($ticket['category'])); ?></div>
                    </div>
                </div>
            </div>

            <!-- Original Issue -->
            <div class="content-section">
                <h2 class="section-title">Original Issue</h2>
                <div class="message-box">
                    <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                </div>

                <?php if (!empty($ticket['attachments'])): ?>
                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--text-secondary);">
                            📎 Attachments (<?php echo count($ticket['attachments']); ?>)
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <?php foreach ($ticket['attachments'] as $attachment): ?>
                                <a href="uploads/<?php echo htmlspecialchars($attachment['stored_name']); ?>"
                                   target="_blank"
                                   download="<?php echo htmlspecialchars($attachment['original_name']); ?>"
                                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); transition: var(--transition);">
                                    <span style="font-size: 1.5rem;">📄</span>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 500;"><?php echo htmlspecialchars($attachment['original_name']); ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                            <?php echo number_format($attachment['size'] / 1024, 1); ?> KB
                                        </div>
                                    </div>
                                    <span style="color: var(--accent-primary);">⬇️ Download</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="two-col">
                <!-- Responses Section -->
                <div class="content-section">
                    <h2 class="section-title">Responses to Customer</h2>

                    <div id="responseMessage"></div>

                    <?php if (empty($ticket['responses'])): ?>
                        <div class="empty-state">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">💬</div>
                            <p>No responses sent yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_reverse($ticket['responses']) as $response): ?>
                            <div class="response-item">
                                <div class="response-header">
                                    <span><strong>Support Team</strong></span>
                                    <span><?php echo htmlspecialchars($response['timestamp']); ?></span>
                                </div>
                                <div class="response-content">
                                    <?php echo nl2br(htmlspecialchars($response['message'])); ?>
                                </div>
                                <?php if (!empty($response['attachments'])): ?>
                                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border-color);">
                                        <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                            📎 Attachments:
                                        </div>
                                        <?php foreach ($response['attachments'] as $attachment): ?>
                                            <a href="uploads/<?php echo htmlspecialchars($attachment['stored_name']); ?>"
                                               target="_blank"
                                               download="<?php echo htmlspecialchars($attachment['original_name']); ?>"
                                               style="display: inline-block; margin-right: 0.5rem; margin-bottom: 0.5rem; padding: 0.5rem 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 6px; text-decoration: none; color: var(--accent-primary); font-size: 0.9rem;">
                                                📄 <?php echo htmlspecialchars($attachment['original_name']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="action-form">
                        <form id="responseForm" enctype="multipart/form-data">
                            <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket_id); ?>">
                            <input type="hidden" name="action" value="add_response">

                            <div class="form-group">
                                <label for="responseMessage">Send Response to Customer</label>
                                <textarea id="responseMessageInput" name="message" required
                                          placeholder="Type your response to the customer..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="responseAttachments">Attachments (Optional)</label>
                                <input type="file" id="responseAttachments" name="attachments[]" multiple
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip,.log"
                                       style="width: 100%; padding: 0.875rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-family: inherit; font-size: 1rem; cursor: pointer;">
                                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.85rem;">
                                    Max 5 files, 10MB each
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary">📧 Send Response</button>
                        </form>
                    </div>
                </div>

                <!-- Internal Notes Section -->
                <div class="content-section">
                    <h2 class="section-title">Internal Notes</h2>

                    <div id="noteMessage"></div>

                    <?php if (empty($ticket['notes'])): ?>
                        <div class="empty-state">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
                            <p>No internal notes yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_reverse($ticket['notes']) as $note): ?>
                            <div class="note-item">
                                <div class="note-header">
                                    <span><strong><?php echo htmlspecialchars($note['author'] ?? 'Support Team'); ?></strong></span>
                                    <span><?php echo htmlspecialchars($note['timestamp']); ?></span>
                                </div>
                                <div class="note-content">
                                    <?php echo nl2br(htmlspecialchars($note['content'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="action-form">
                        <form id="noteForm">
                            <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket_id); ?>">
                            <input type="hidden" name="action" value="add_note">

                            <div class="form-group">
                                <label for="noteContent">Add Internal Note</label>
                                <textarea id="noteContentInput" name="content" required
                                          placeholder="Add notes for internal tracking (not visible to customer)..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="noteAuthor">Your Name</label>
                                <input type="text" id="noteAuthor" name="author"
                                       style="width: 100%; padding: 0.875rem; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-family: inherit; font-size: 1rem;"
                                       placeholder="e.g., John Doe" required>
                            </div>

                            <button type="submit" class="btn btn-primary">📝 Add Note</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Status Section -->
            <div class="content-section">
                <h2 class="section-title">Update Ticket Status</h2>

                <div id="statusMessage"></div>

                <form id="statusForm" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
                    <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket_id); ?>">
                    <input type="hidden" name="action" value="update_status">

                    <div class="form-group" style="flex: 1; min-width: 250px; margin-bottom: 0;">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Open" <?php echo $ticket['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                            <option value="In Progress" <?php echo $ticket['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Waiting for Customer" <?php echo $ticket['status'] === 'Waiting for Customer' ? 'selected' : ''; ?>>Waiting for Customer</option>
                            <option value="Resolved" <?php echo $ticket['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="Closed" <?php echo $ticket['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-bottom: 0;">Update Status</button>
                </form>
            </div>
        </div>
    </main>

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

        // Response Form Handler
        document.getElementById('responseForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const messageDiv = document.getElementById('responseMessage');

            try {
                const response = await fetch('update_ticket.php', {
                    method: 'POST',
                    body: formData
                });

                // Check if response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // Get response text first to debug
                const text = await response.text();

                // Try to parse as JSON
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }

                if (result.success) {
                    messageDiv.innerHTML = '<div class="success-message">' + result.message + '</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    messageDiv.innerHTML = '<div class="error-message">' + result.message + '</div>';
                }
            } catch (error) {
                console.error('Response form error:', error);
                messageDiv.innerHTML = '<div class="error-message">An error occurred. Please try again.</div>';
            }
        });

        // Note Form Handler
        document.getElementById('noteForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const messageDiv = document.getElementById('noteMessage');

            try {
                const response = await fetch('update_ticket.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }

                if (result.success) {
                    messageDiv.innerHTML = '<div class="success-message">' + result.message + '</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    messageDiv.innerHTML = '<div class="error-message">' + result.message + '</div>';
                }
            } catch (error) {
                console.error('Note form error:', error);
                messageDiv.innerHTML = '<div class="error-message">An error occurred. Please try again.</div>';
            }
        });

        // Status Form Handler
        document.getElementById('statusForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const messageDiv = document.getElementById('statusMessage');

            try {
                const response = await fetch('update_ticket.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }

                if (result.success) {
                    messageDiv.innerHTML = '<div class="success-message">' + result.message + '</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    messageDiv.innerHTML = '<div class="error-message">' + result.message + '</div>';
                }
            } catch (error) {
                console.error('Status form error:', error);
                messageDiv.innerHTML = '<div class="error-message">An error occurred. Please try again.</div>';
            }
        });

        // Check if device is mobile
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
                || window.innerWidth <= 768;
        }

        // Service Worker Registration and Push Notifications
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', async () => {
                try {
                    const registration = await navigator.serviceWorker.register('sw.js');
                    console.log('Service Worker registered');

                    // Request notification permission on mobile devices only
                    if (isMobileDevice() && 'Notification' in window) {
                        if (Notification.permission === 'granted') {
                            subscribeToPushNotifications(registration);
                        }
                    }
                } catch (error) {
                    console.log('Service Worker registration failed:', error);
                }
            });
        }

        // Convert VAPID key from base64 to Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Subscribe to push notifications
        async function subscribeToPushNotifications(registration) {
            try {
                if (!('pushManager' in registration)) {
                    return;
                }

                // VAPID public key
                const vapidPublicKey = 'BPgIfLcgqH3VWy1ICrxuYV-o4EGwlcloVfOKlt9ZWHxq4qjU69pc-mDx28AyouFpGyTHe87YCPzapTD5Yfkj14I';

                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
                });

                await fetch('save_subscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(subscription)
                });

                console.log('Push subscription successful');
            } catch (error) {
                console.error('Error subscribing to push notifications:', error);
            }
        }
    </script>
</body>
</html>
