<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zopollo IT Support - Professional IT solutions and support services">
    <meta name="theme-color" content="#0066ff">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <link rel="icon" type="image/svg+xml" href="icon-192.svg">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="icon-192.svg">

    <title>Zopollo IT Support</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="styles.css">
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
                <a href="#home" class="nav-link">
                    <span class="nav-icon">🏠</span>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="#services" class="nav-link">
                    <span class="nav-icon">🛠️</span>
                    Services
                </a>
            </li>
            <li class="nav-item">
                <a href="#support" class="nav-link">
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
                <a href="#status" class="nav-link">
                    <span class="nav-icon">📊</span>
                    System Status
                </a>
            </li>
            <li class="nav-item">
                <a href="#knowledge" class="nav-link">
                    <span class="nav-icon">📚</span>
                    Knowledge Base
                </a>
            </li>
            <li class="nav-item">
                <a href="#contact" class="nav-link">
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
                <a href="#home">🏠 Home</a>
                <a href="#services">🛠️ Services</a>
                <a href="#support">🎫 Submit Ticket</a>
                <a href="view_tickets.php">📋 View Tickets</a>
                <a href="#status">📊 Status</a>
                <a href="#knowledge">📚 Knowledge</a>
                <a href="#contact">📧 Contact</a>
            </nav>

            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <span id="themeIcon">🌙</span>
                    <span id="themeText">Dark</span>
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="hero-content">
                <h1>IT Support Excellence</h1>
                <p>Professional IT solutions and 24/7 support for your business needs</p>
                <a href="#support" class="cta-button">Submit a Support Ticket</a>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services" id="services">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <h3>Technical Support</h3>
                    <p>24/7 technical assistance for hardware, software, and network issues. Our expert team is always ready to help.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🔒</div>
                    <h3>Cybersecurity</h3>
                    <p>Comprehensive security solutions to protect your data and infrastructure from threats and vulnerabilities.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">☁️</div>
                    <h3>Cloud Solutions</h3>
                    <p>Cloud migration, management, and optimization services for scalable and reliable infrastructure.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>System Maintenance</h3>
                    <p>Proactive monitoring and maintenance to keep your systems running smoothly and efficiently.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">📱</div>
                    <h3>Mobile Device Management</h3>
                    <p>Secure and manage mobile devices across your organization with enterprise-grade MDM solutions.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🎓</div>
                    <h3>IT Training</h3>
                    <p>Comprehensive training programs to empower your team with the latest IT skills and best practices.</p>
                </div>
            </div>
        </section>

        <!-- Submit Ticket Section -->
        <section class="contact" id="support">
            <h2 class="section-title">Submit a Support Ticket</h2>
            <form class="contact-form" id="ticketForm" method="POST" action="submit_ticket.php">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>

                <div class="form-group">
                    <label for="priority">Priority Level *</label>
                    <select id="priority" name="priority" required style="width: 100%; padding: 0.875rem; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-family: inherit; font-size: 1rem;">
                        <option value="low">Low - General inquiry</option>
                        <option value="medium">Medium - Non-critical issue</option>
                        <option value="high">High - System impacting</option>
                        <option value="critical">Critical - Service down</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Issue Category *</label>
                    <select id="category" name="category" required style="width: 100%; padding: 0.875rem; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-family: inherit; font-size: 1rem;">
                        <option value="hardware">Hardware Issue</option>
                        <option value="software">Software Problem</option>
                        <option value="network">Network/Connectivity</option>
                        <option value="security">Security Concern</option>
                        <option value="email">Email Issue</option>
                        <option value="account">Account/Password</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" id="subject" name="subject" required>
                </div>

                <div class="form-group">
                    <label for="message">Describe Your Issue *</label>
                    <textarea id="message" name="message" required></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Ticket</button>

                <div id="formMessage" style="margin-top: 1rem; text-align: center; font-weight: 600;"></div>
            </form>
        </section>

        <!-- System Status Section -->
        <section class="features" id="status">
            <div class="features-container">
                <h2 class="section-title">System Status</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-number">✓</div>
                        <h4>Email Services</h4>
                        <p style="color: #28a745; font-weight: 600;">Operational</p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-number">✓</div>
                        <h4>Cloud Infrastructure</h4>
                        <p style="color: #28a745; font-weight: 600;">Operational</p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-number">✓</div>
                        <h4>Network Services</h4>
                        <p style="color: #28a745; font-weight: 600;">Operational</p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-number">✓</div>
                        <h4>Security Systems</h4>
                        <p style="color: #28a745; font-weight: 600;">Operational</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Knowledge Base Section -->
        <section class="services" id="knowledge">
            <h2 class="section-title">Knowledge Base</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">❓</div>
                    <h3>Getting Started</h3>
                    <p>Learn the basics of our IT support platform and how to get the most out of our services.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🔐</div>
                    <h3>Security Best Practices</h3>
                    <p>Essential security tips and guidelines to protect your accounts and data from threats.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">🐛</div>
                    <h3>Troubleshooting Guides</h3>
                    <p>Step-by-step guides to resolve common technical issues quickly and efficiently.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">📖</div>
                    <h3>Documentation</h3>
                    <p>Comprehensive documentation for all our services, tools, and integrations.</p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact" id="contact">
            <h2 class="section-title">Contact Information</h2>
            <div class="contact-form">
                <div style="text-align: center; line-height: 2;">
                    <h3 style="margin-bottom: 1.5rem;">Get in Touch</h3>
                    <p><strong>24/7 Support Hotline:</strong></p>
                    <p style="font-size: 1.5rem; color: var(--accent-primary);"><a href="tel:+1234567890" style="color: var(--accent-primary); text-decoration: none;">+1 (234) 567-890</a></p>

                    <p style="margin-top: 2rem;"><strong>Email:</strong></p>
                    <p><a href="mailto:support@zopollo.com" style="color: var(--accent-primary); text-decoration: none;">support@zopollo.com</a></p>

                    <p style="margin-top: 2rem;"><strong>Business Hours:</strong></p>
                    <p>24/7 Emergency Support Available</p>
                    <p>Regular Hours: Monday - Friday, 8:00 AM - 6:00 PM</p>

                    <p style="margin-top: 2rem;"><strong>Address:</strong></p>
                    <p>123 Tech Street<br>Innovation District<br>Tech City, TC 12345</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="zopollo-logo.svg" alt="Zopollo IT">
                </div>
                <p>&copy; <?php echo date('Y'); ?> Zopollo IT Solutions. All rights reserved.</p>
                <p style="margin-top: 0.5rem; font-size: 0.85rem;">
                    <a href="#" style="color: var(--accent-primary); text-decoration: none; margin: 0 0.5rem;">Privacy Policy</a> |
                    <a href="#" style="color: var(--accent-primary); text-decoration: none; margin: 0 0.5rem;">Terms of Service</a> |
                    <a href="#" style="color: var(--accent-primary); text-decoration: none; margin: 0 0.5rem;">Cookie Policy</a>
                </p>
            </div>
        </footer>
    </main>

    <!-- PWA Install Prompt -->
    <div class="install-prompt" id="installPrompt">
        <div class="install-content">
            <div class="install-icon">📱</div>
            <div class="install-text">
                <h4>Install Zopollo IT Support</h4>
                <p>Get quick access to support services</p>
            </div>
        </div>
        <div class="install-actions">
            <button class="install-btn" id="installBtn">Install</button>
            <button class="install-btn dismiss-btn" id="dismissBtn">Not Now</button>
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

        // PWA Installation (only show on mobile)
        let deferredPrompt;
        const installPrompt = document.getElementById('installPrompt');
        const installBtn = document.getElementById('installBtn');
        const dismissBtn = document.getElementById('dismissBtn');

        // Check if device is mobile
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
                || window.innerWidth <= 768;
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Only show install prompt on mobile devices
            if (isMobileDevice()) {
                installPrompt.classList.add('show');
            }
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                installPrompt.classList.remove('show');
            }
        });

        dismissBtn.addEventListener('click', () => {
            installPrompt.classList.remove('show');
        });

        window.addEventListener('appinstalled', () => {
            installPrompt.classList.remove('show');
            deferredPrompt = null;
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(registration => console.log('Service Worker registered'))
                    .catch(error => console.log('Service Worker registration failed:', error));
            });
        }

        // Form Submission Handler
        document.getElementById('ticketForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const formMessage = document.getElementById('formMessage');

            try {
                const response = await fetch('submit_ticket.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    formMessage.style.color = '#28a745';
                    formMessage.textContent = result.message;
                    e.target.reset();
                } else {
                    formMessage.style.color = '#dc3545';
                    formMessage.textContent = result.message;
                }
            } catch (error) {
                formMessage.style.color = '#dc3545';
                formMessage.textContent = 'An error occurred. Please try again.';
            }
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
