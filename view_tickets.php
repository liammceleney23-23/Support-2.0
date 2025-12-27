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

// Reverse to show newest first
$tickets = array_reverse($tickets);
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
    </style>
</head>
<body>
    <div class="ticket-container">
        <a href="index.php" class="back-link">← Back to Home</a>

        <div class="ticket-header">
            <h1 style="font-family: 'Orbitron', monospace; font-size: 2rem; margin-bottom: 0.5rem;">Support Tickets Dashboard</h1>
            <p style="color: var(--text-secondary);">View and manage all support tickets</p>

            <div class="ticket-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($tickets); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Total Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($tickets, function($t) { return $t['priority'] === 'critical'; })); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Critical</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($tickets, function($t) { return $t['priority'] === 'high'; })); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">High Priority</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($tickets, function($t) { return $t['priority'] === 'medium'; })); ?></div>
                    <div style="color: var(--text-secondary); margin-top: 0.5rem;">Medium Priority</div>
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
            <?php foreach ($tickets as $ticket): ?>
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
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>
