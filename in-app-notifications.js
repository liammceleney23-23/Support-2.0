/**
 * In-App Notification System
 * Provides real-time update notifications for iOS and fallback for all devices
 */

class InAppNotifications {
    constructor(options = {}) {
        this.pollInterval = options.pollInterval || 30000; // Check every 30 seconds
        this.userEmail = options.userEmail || localStorage.getItem('user_email') || '';
        this.lastCheck = this.getLastCheck();
        this.isPolling = false;
        this.pollTimer = null;
        this.notifications = [];
        this.maxNotifications = 5;

        this.init();
    }

    init() {
        // Create notification container
        this.createNotificationContainer();

        // Start polling if page is visible
        if (document.visibilityState === 'visible') {
            this.startPolling();
        }

        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.startPolling();
            } else {
                this.stopPolling();
            }
        });

        // Stop polling when page is closed
        window.addEventListener('beforeunload', () => {
            this.stopPolling();
        });
    }

    createNotificationContainer() {
        if (document.getElementById('in-app-notifications')) {
            return; // Already exists
        }

        const container = document.createElement('div');
        container.id = 'in-app-notifications';
        container.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
            width: 100%;
            pointer-events: none;
        `;
        document.body.appendChild(container);

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .in-app-notification {
                background: var(--bg-secondary, #1a1f3a);
                border: 2px solid var(--accent-primary, #0066ff);
                border-radius: 12px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 4px 20px rgba(0, 102, 255, 0.3);
                pointer-events: auto;
                cursor: pointer;
                transition: all 0.3s ease;
                animation: slideIn 0.3s ease-out;
            }

            .in-app-notification:hover {
                transform: translateX(-5px);
                box-shadow: 0 6px 30px rgba(0, 102, 255, 0.5);
            }

            .in-app-notification-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .in-app-notification-icon {
                font-size: 1.5rem;
                margin-right: 0.5rem;
            }

            .in-app-notification-title {
                font-weight: 600;
                color: var(--accent-primary, #0066ff);
                font-size: 0.95rem;
            }

            .in-app-notification-close {
                background: transparent;
                border: none;
                color: var(--text-secondary, #8892b0);
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: color 0.2s ease;
            }

            .in-app-notification-close:hover {
                color: var(--accent-primary, #0066ff);
            }

            .in-app-notification-body {
                color: var(--text-primary, #e6f1ff);
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .in-app-notification-ticket {
                font-family: 'Orbitron', monospace;
                color: var(--accent-secondary, #00f0ff);
                font-weight: 700;
                margin-top: 0.5rem;
            }

            .in-app-notification-time {
                color: var(--text-secondary, #8892b0);
                font-size: 0.75rem;
                margin-top: 0.5rem;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }

            .in-app-notification.removing {
                animation: slideOut 0.3s ease-out forwards;
            }

            @media (max-width: 768px) {
                #in-app-notifications {
                    top: 70px;
                    right: 10px;
                    left: 10px;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(style);
    }

    getLastCheck() {
        const stored = localStorage.getItem('last_notification_check');
        return stored || new Date().toISOString().slice(0, 19).replace('T', ' ');
    }

    saveLastCheck(timestamp) {
        this.lastCheck = timestamp;
        localStorage.setItem('last_notification_check', timestamp);
    }

    async checkForUpdates() {
        try {
            const url = `check_updates.php?last_check=${encodeURIComponent(this.lastCheck)}&email=${encodeURIComponent(this.userEmail)}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.success && data.has_updates) {
                // Save the server time as last check
                this.saveLastCheck(data.server_time);

                // Show notifications for each update
                data.updates.forEach(update => {
                    this.showNotification(update);
                });
            } else if (data.success) {
                // Update last check time even if no updates
                this.saveLastCheck(data.server_time);
            }
        } catch (error) {
            console.error('Error checking for updates:', error);
        }
    }

    showNotification(update) {
        // Limit number of notifications
        if (this.notifications.length >= this.maxNotifications) {
            const oldest = this.notifications.shift();
            this.removeNotification(oldest.element);
        }

        const container = document.getElementById('in-app-notifications');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = 'in-app-notification';

        const icon = update.type === 'new_response' ? '💬' : '📊';

        notification.innerHTML = `
            <div class="in-app-notification-header">
                <div style="display: flex; align-items: center;">
                    <span class="in-app-notification-icon">${icon}</span>
                    <span class="in-app-notification-title">Ticket Update</span>
                </div>
                <button class="in-app-notification-close" aria-label="Close">&times;</button>
            </div>
            <div class="in-app-notification-body">
                ${update.message}
                <div class="in-app-notification-ticket">${update.ticket_id}</div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.25rem;">
                    ${update.subject}
                </div>
                <div class="in-app-notification-time">
                    ${this.formatTime(update.timestamp)}
                </div>
            </div>
        `;

        // Add click handler to navigate to ticket
        notification.addEventListener('click', (e) => {
            if (!e.target.classList.contains('in-app-notification-close')) {
                window.location.href = update.url;
            }
        });

        // Add close button handler
        const closeBtn = notification.querySelector('.in-app-notification-close');
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.removeNotification(notification);
        });

        container.appendChild(notification);

        // Track notification
        this.notifications.push({
            element: notification,
            timestamp: new Date()
        });

        // Auto-remove after 10 seconds
        setTimeout(() => {
            this.removeNotification(notification);
        }, 10000);

        // Play sound if available
        this.playNotificationSound();
    }

    removeNotification(element) {
        if (!element || !element.parentNode) return;

        element.classList.add('removing');
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
            // Remove from tracking
            this.notifications = this.notifications.filter(n => n.element !== element);
        }, 300);
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds

        if (diff < 60) return 'Just now';
        if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;

        return date.toLocaleString();
    }

    playNotificationSound() {
        // Create a subtle notification sound
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        } catch (error) {
            // Silent fail if audio not supported
        }
    }

    startPolling() {
        if (this.isPolling) return;

        this.isPolling = true;

        // Check immediately
        this.checkForUpdates();

        // Then check at intervals
        this.pollTimer = setInterval(() => {
            this.checkForUpdates();
        }, this.pollInterval);

        console.log('In-app notifications: Polling started');
    }

    stopPolling() {
        if (!this.isPolling) return;

        this.isPolling = false;

        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }

        console.log('In-app notifications: Polling stopped');
    }

    setUserEmail(email) {
        this.userEmail = email;
        localStorage.setItem('user_email', email);
    }
}

// Auto-initialize if not already done
if (typeof window !== 'undefined' && !window.inAppNotifications) {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.inAppNotifications = new InAppNotifications();
        });
    } else {
        window.inAppNotifications = new InAppNotifications();
    }
}
