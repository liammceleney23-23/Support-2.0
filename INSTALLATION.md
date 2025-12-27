# Quick Installation Guide

## Zopollo IT Support PWA - Setup Instructions

### Prerequisites
- Web server with PHP 7.4+ (Apache/Nginx)
- HTTPS certificate (required for PWA features)
- Write permissions for ticket storage

### Quick Start (Local Development)

1. **Navigate to the directory:**
   ```bash
   cd C:\Temp\Web\it-support-pwa
   ```

2. **Start PHP development server:**
   ```bash
   php -S localhost:8000
   ```

3. **Open in browser:**
   ```
   http://localhost:8000
   ```

### Production Deployment

#### Option 1: Apache Server

1. **Upload files to your web server** (via FTP/SFTP)
   - Upload all files to your web root directory (e.g., `/var/www/html/` or `/public_html/`)

2. **Set correct permissions:**
   ```bash
   # Set file permissions
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;

   # Make tickets.json writable (will be created automatically)
   touch tickets.json
   chmod 666 tickets.json
   ```

3. **Enable Apache modules:**
   ```bash
   sudo a2enmod rewrite
   sudo a2enmod headers
   sudo a2enmod expires
   sudo a2enmod deflate
   sudo systemctl restart apache2
   ```

4. **Configure virtual host** (if needed):
   ```apache
   <VirtualHost *:80>
       ServerName yourdomain.com
       DocumentRoot /var/www/html/it-support-pwa

       <Directory /var/www/html/it-support-pwa>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

5. **Enable HTTPS** (required for PWA):
   ```bash
   # Using Let's Encrypt
   sudo apt-get install certbot python3-certbot-apache
   sudo certbot --apache -d yourdomain.com
   ```

#### Option 2: Nginx Server

1. **Upload files to server**

2. **Configure Nginx:**
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/html/it-support-pwa;
       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```

3. **Enable HTTPS:**
   ```bash
   sudo certbot --nginx -d yourdomain.com
   ```

### Configuration

#### 1. Update Contact Information

Edit `index.php` and replace:
- `+1 (234) 567-890` with your phone number
- `support@zopollo.com` with your email
- Update business address in contact section

Edit `submit_ticket.php` and update:
- Email addresses for notifications
- SMTP settings if needed

#### 2. Enable Email Notifications (Optional)

In `submit_ticket.php`, uncomment these lines:
```php
// Line ~100: Enable support team notification
mail($to, $email_subject, $email_message, $email_headers);

// Line ~130: Enable customer confirmation
mail($email, $customer_subject, $customer_message, $customer_headers);
```

#### 3. Configure SMTP (Recommended for Production)

Install PHPMailer:
```bash
composer require phpmailer/phpmailer
```

Update `submit_ticket.php` to use SMTP instead of mail() function.

#### 4. Database Setup (Optional - Recommended for Production)

Instead of JSON file storage, use a database:

```sql
CREATE DATABASE zopollo_support;
USE zopollo_support;

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id VARCHAR(50) UNIQUE NOT NULL,
    timestamp DATETIME NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    priority ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    category VARCHAR(50) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Open',
    ip_address VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_priority (priority),
    INDEX idx_status (status),
    INDEX idx_timestamp (timestamp)
);
```

Update `submit_ticket.php` with database connection.

### Testing the PWA

1. **Test Basic Functionality:**
   - Open the site in a browser
   - Submit a test ticket
   - Check `tickets.json` or view at `view_tickets.php`

2. **Test PWA Installation:**
   - Must use HTTPS
   - Open in Chrome/Edge
   - Look for install prompt
   - Check Chrome DevTools > Application > Manifest

3. **Test Offline Mode:**
   - Open the app
   - Open DevTools > Network
   - Set to "Offline"
   - Navigate the app (cached pages should work)

4. **Test Service Worker:**
   - Open DevTools > Application > Service Workers
   - Verify service worker is registered and activated

### File Structure After Installation

```
your-web-root/
├── index.php                 # Main app
├── view_tickets.php          # Admin ticket viewer
├── submit_ticket.php         # Form handler
├── manifest.json             # PWA manifest
├── sw.js                     # Service worker
├── styles.css                # Styles
├── zopollo-logo.svg          # Main logo
├── icon-192.svg              # App icon 192x192
├── icon-512.svg              # App icon 512x512
├── favicon.svg               # Favicon
├── .htaccess                 # Apache config
├── tickets.json              # Ticket storage (auto-created)
├── README.md                 # Documentation
└── INSTALLATION.md           # This file
```

### Security Checklist

- [ ] HTTPS enabled (SSL certificate installed)
- [ ] File permissions set correctly (644 for files, 755 for directories)
- [ ] `.htaccess` protecting sensitive files
- [ ] Database credentials secured (if using database)
- [ ] Email authentication configured
- [ ] Add authentication to `view_tickets.php` (important!)
- [ ] Rate limiting implemented (optional but recommended)
- [ ] Regular backups configured

### Admin Access

**View Tickets:**
Navigate to: `https://yourdomain.com/view_tickets.php`

⚠️ **IMPORTANT:** Add authentication to this page before going live!

Example basic auth in `.htaccess`:
```apache
<Files "view_tickets.php">
    AuthType Basic
    AuthName "Admin Access"
    AuthUserFile /path/to/.htpasswd
    Require valid-user
</Files>
```

Create password file:
```bash
htpasswd -c /path/to/.htpasswd admin
```

### Troubleshooting

**Issue: PWA won't install**
- Solution: Ensure HTTPS is enabled. PWA requires secure connection.

**Issue: Service worker not registering**
- Solution: Check browser console for errors. Clear cache and hard reload.

**Issue: Tickets not saving**
- Solution: Check file permissions on directory and `tickets.json`

**Issue: Emails not sending**
- Solution: Configure PHP mail settings or use SMTP. Check server logs.

**Issue: Theme not persisting**
- Solution: Enable browser localStorage. Check privacy settings.

### Support

For issues or questions:
- Email: support@zopollo.com
- Phone: +1 (234) 567-890

### Next Steps

1. Customize branding and colors
2. Add authentication to admin pages
3. Configure email notifications
4. Set up database (recommended)
5. Configure backups
6. Add analytics (optional)
7. Implement rate limiting
8. Test thoroughly before going live

---

**Ready to Go Live?**

1. Complete all security checklist items
2. Test all functionality
3. Configure production email settings
4. Set up monitoring and backups
5. Update contact information
6. Test PWA installation on mobile devices

Your Zopollo IT Support PWA is now ready to use! 🚀
