<?php
// Simple ticket viewer for admin/support team
// In production, add proper authentication!

$tickets_file = 'tickets.json';
$tickets = [];

if (file_exists($tickets_file)) {
    $tickets_content = file_get_contents($tickets_file);
    $tickets = json_decode($tickets_content, true);
    if (!is_array($tickets)) {
        $tickets = [];
    }
}

// Separate tickets into open and closed
$open_tickets = [];
$closed_tickets = [];

foreach ($tickets as $ticket) {
    if ($ticket['status'] === 'Closed') {
        $closed_tickets[] = $ticket;
    } else {
        $open_tickets[] = $ticket;
    }
}

// Reverse to show newest first
$open_tickets = array_reverse($open_tickets);
$closed_tickets = array_reverse($closed_tickets);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Support Tickets - Zopollo IT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .ticket-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .ticket-header {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px var(--shadow);
        }
        .ticket-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .stat-card {
            background: var(--bg-tertiary);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-primary);
        }
        .ticket-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px var(--shadow);
            transition: var(--transition);
        }
        .ticket-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px var(--shadow);
        }
        .ticket-id {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--accent-primary);
            font-size: 1.1rem;
        }
        .priority-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .priority-critical {
            background: #dc3545;
            color: white;
        }
        .priority-high {
            background: #fd7e14;
            color: white;
        }
        .priority-medium {
            background: #ffc107;
            color: #000;
        }
        .priority-low {
            background: #28a745;
            color: white;
        }
        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .ticket-info {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        .ticket-message {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-tertiary);
            border-radius: 8px;
            line-height: 1.6;
        }
        .no-tickets {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }
        .back-link {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--accent-primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        .back-link:hover {
            background: var(--accent-secondary);
            transform: translateY(-2px);
        }
        .section-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }
        .section-header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .ticket-card.minimized {
            padding: 1rem 1.5rem;
        }
        .ticket-card.minimized .ticket-details {
            display: none;
        }
        .ticket-card.minimized:hover {
            transform: none;
        }
        .minimized-view {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .minimized-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .expand-btn {
            padding: 0.5rem 1rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        .expand-btn:hover {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }
        .status-closed {
            background: #6c757d;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
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
                    <!-- Z Icon -->
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
                    <!-- Text -->
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

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <a href="index.php#home">🏠 Home</a>
                <a href="index.php">🎫 Submit Ticket</a>
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

        <div class="ticket-header">
            <h1 style="font-family: 'Orbitron', monospace; font-size: 2rem; margin-bottom: 0.5rem;">Support Tickets Dashboard</h1>
            <p style="color: var(--text-secondary);">View and manage all support tickets</p>

            <div class="ticket-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($tickets); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Total Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($open_tickets); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Open Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($closed_tickets); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Closed Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($tickets, function($t) { return $t['priority'] === 'critical' && $t['status'] !== 'Closed'; })); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Critical Open</div>
                </div>
            </div>
        </div>

        <?php if (empty($tickets)): ?>
            <div class="no-tickets">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <h2>No tickets yet</h2>
                <p>Submitted support tickets will appear here.</p>
            </div>
        <?php else: ?>

            <!-- Open Tickets Section -->
            <div class="section-header">
                <h2>
                    <span>🎫</span>
                    <span>Open Tickets</span>
                    <span style="font-size: 1rem; font-weight: 400; color: var(--text-secondary); margin-left: 0.5rem;">
                        (<?php echo count($open_tickets); ?>)
                    </span>
                </h2>
            </div>

            <?php if (empty($open_tickets)): ?>
                <div class="no-tickets" style="padding: 2rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <p style="color: var(--text-secondary);">No open tickets - all clear!</p>
                </div>
            <?php else: ?>
                <?php foreach ($open_tickets as $ticket): ?>
                    <div class="ticket-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <div class="ticket-id"><?php echo htmlspecialchars($ticket['ticket_id']); ?></div>
                                <div style="margin-top: 0.5rem;">
                                    <span class="priority-badge priority-<?php echo htmlspecialchars($ticket['priority']); ?>">
                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                    </span>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">
                                        <?php echo ucfirst(htmlspecialchars($ticket['category'])); ?>
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right; font-size: 0.85rem; color: var(--text-secondary);">
                                <?php echo htmlspecialchars($ticket['timestamp']); ?>
                            </div>
                        </div>

                        <h3 style="margin-bottom: 1rem; font-size: 1.25rem;">
                            <?php echo htmlspecialchars($ticket['subject']); ?>
                        </h3>

                        <div class="ticket-grid">
                            <div class="ticket-info">
                                <strong>Name:</strong><br>
                                <?php echo htmlspecialchars($ticket['name']); ?>
                            </div>
                            <div class="ticket-info">
                                <strong>Email:</strong><br>
                                <a href="mailto:<?php echo htmlspecialchars($ticket['email']); ?>" style="color: var(--accent-primary);">
                                    <?php echo htmlspecialchars($ticket['email']); ?>
                                </a>
                            </div>
                            <div class="ticket-info">
                                <strong>Phone:</strong><br>
                                <?php echo !empty($ticket['phone']) ? htmlspecialchars($ticket['phone']) : 'N/A'; ?>
                            </div>
                            <div class="ticket-info">
                                <strong>Status:</strong><br>
                                <span style="color: var(--accent-primary); font-weight: 600;">
                                    <?php echo htmlspecialchars($ticket['status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="ticket-message">
                            <strong>Message:</strong><br>
                            <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <a href="manage_ticket.php?id=<?php echo urlencode($ticket['ticket_id']); ?>"
                               class="back-link"
                               style="display: inline-block; text-decoration: none;">
                                🔧 Manage Ticket
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Closed Tickets Section -->
            <?php if (!empty($closed_tickets)): ?>
                <div class="section-header">
                    <h2>
                        <span>📦</span>
                        <span>Closed Tickets</span>
                        <span style="font-size: 1rem; font-weight: 400; color: var(--text-secondary); margin-left: 0.5rem;">
                            (<?php echo count($closed_tickets); ?>)
                        </span>
                    </h2>
                </div>

                <?php foreach ($closed_tickets as $index => $ticket): ?>
                    <div class="ticket-card minimized" id="ticket-<?php echo $index; ?>">
                        <!-- Minimized View -->
                        <div class="minimized-view">
                            <div class="minimized-info">
                                <div class="ticket-id" style="font-size: 1rem;"><?php echo htmlspecialchars($ticket['ticket_id']); ?></div>
                                <span class="status-closed">Closed</span>
                                <h3 style="margin: 0; font-size: 1rem; font-weight: 500;">
                                    <?php echo htmlspecialchars($ticket['subject']); ?>
                                </h3>
                                <span style="color: var(--text-secondary); font-size: 0.85rem;">
                                    <?php echo htmlspecialchars($ticket['timestamp']); ?>
                                </span>
                            </div>
                            <button class="expand-btn" onclick="toggleTicket(<?php echo $index; ?>)">
                                <span class="expand-text">Expand</span>
                            </button>
                        </div>

                        <!-- Full Details (Hidden by default) -->
                        <div class="ticket-details">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                                <div>
                                    <div style="margin-top: 0.5rem;">
                                        <span class="priority-badge priority-<?php echo htmlspecialchars($ticket['priority']); ?>">
                                            <?php echo htmlspecialchars($ticket['priority']); ?>
                                        </span>
                                        <span style="margin-left: 0.5rem; color: var(--text-secondary);">
                                            <?php echo ucfirst(htmlspecialchars($ticket['category'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-grid">
                                <div class="ticket-info">
                                    <strong>Name:</strong><br>
                                    <?php echo htmlspecialchars($ticket['name']); ?>
                                </div>
                                <div class="ticket-info">
                                    <strong>Email:</strong><br>
                                    <a href="mailto:<?php echo htmlspecialchars($ticket['email']); ?>" style="color: var(--accent-primary);">
                                        <?php echo htmlspecialchars($ticket['email']); ?>
                                    </a>
                                </div>
                                <div class="ticket-info">
                                    <strong>Phone:</strong><br>
                                    <?php echo !empty($ticket['phone']) ? htmlspecialchars($ticket['phone']) : 'N/A'; ?>
                                </div>
                            </div>

                            <div class="ticket-message">
                                <strong>Message:</strong><br>
                                <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                            </div>

                            <div style="margin-top: 1.5rem;">
                                <a href="manage_ticket.php?id=<?php echo urlencode($ticket['ticket_id']); ?>"
                                   class="back-link"
                                   style="display: inline-block; text-decoration: none;">
                                    🔧 Manage Ticket
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endif; ?>
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

        // Load saved theme
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

        // Toggle closed ticket expansion
        function toggleTicket(index) {
            const ticketCard = document.getElementById('ticket-' + index);
            const expandBtn = ticketCard.querySelector('.expand-btn');
            const expandText = expandBtn.querySelector('.expand-text');

            if (ticketCard.classList.contains('minimized')) {
                ticketCard.classList.remove('minimized');
                expandText.textContent = 'Collapse';
            } else {
                ticketCard.classList.add('minimized');
                expandText.textContent = 'Expand';
            }
        }
    </script>
</body>
</html>
