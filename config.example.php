<?php
/**
 * Zopollo IT Support - Configuration File
 *
 * Copy this file to config.php and update with your settings
 * DO NOT commit config.php to version control!
 */

// Application Settings
define('APP_NAME', 'Zopollo IT Support');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // 'development' or 'production'

// Contact Information
define('SUPPORT_EMAIL', 'support@zopollo.com');
define('SUPPORT_PHONE', '+1 (234) 567-890');
define('COMPANY_NAME', 'Zopollo IT Solutions');
define('COMPANY_ADDRESS', '123 Tech Street, Innovation District, Tech City, TC 12345');

// Email Settings
define('ENABLE_EMAIL_NOTIFICATIONS', false); // Set to true to enable
define('SMTP_ENABLED', false); // Set to true to use SMTP instead of mail()

// SMTP Configuration (if SMTP_ENABLED is true)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');
define('SMTP_ENCRYPTION', 'tls'); // 'tls' or 'ssl'

// Database Settings (optional - for future use)
define('DB_ENABLED', false); // Set to true to use database instead of JSON
define('DB_HOST', 'localhost');
define('DB_NAME', 'zopollo_support');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Storage Settings
define('STORAGE_METHOD', 'file'); // 'file' or 'database'
define('TICKETS_FILE', 'tickets.json');

// Security Settings
define('ENABLE_RATE_LIMITING', false);
define('MAX_REQUESTS_PER_HOUR', 10);
define('ADMIN_PASSWORD_HASH', ''); // Use password_hash() to generate

// Response Time SLA (in hours)
define('SLA_CRITICAL', 1);
define('SLA_HIGH', 4);
define('SLA_MEDIUM', 24);
define('SLA_LOW', 48);

// Feature Flags
define('ENABLE_PUSH_NOTIFICATIONS', false);
define('ENABLE_TICKET_ATTACHMENTS', false);
define('ENABLE_LIVE_CHAT', false);

// Logging
define('ENABLE_LOGGING', true);
define('LOG_FILE', 'app.log');

// Timezone
date_default_timezone_set('America/New_York'); // Update to your timezone

?>
