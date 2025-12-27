# Zopollo IT Support - Progressive Web App

A modern, responsive IT support progressive web app built with PHP, featuring dark/light mode, offline functionality, and mobile installation support.

## Features

- **Progressive Web App (PWA)**: Installable on mobile and desktop devices
- **Dark/Light Mode**: Automatic theme switching with user preference saved
- **Offline Support**: Service worker enables offline functionality
- **IT Support Ticketing**: Submit and track support tickets
- **System Status Dashboard**: Real-time system status monitoring
- **Knowledge Base**: Access to IT support documentation
- **Responsive Design**: Works seamlessly on all devices
- **Modern UI**: Beautiful gradient design with smooth animations

## Requirements

- PHP 7.4 or higher
- Apache web server with mod_rewrite enabled
- Modern web browser with PWA support

## Installation

1. **Upload Files**
   - Upload all files to your web server directory
   - Ensure proper file permissions (644 for files, 755 for directories)

2. **Configure Apache**
   - Ensure `.htaccess` file is present
   - Enable `mod_rewrite` if not already enabled:
     ```bash
     sudo a2enmod rewrite
     sudo systemctl restart apache2
     ```

3. **Set Permissions**
   ```bash
   chmod 644 *.php *.css *.js *.json *.svg
   chmod 755 .
   chmod 666 tickets.json  # If using file-based ticket storage
   ```

4. **Configure Email (Optional)**
   - Edit `submit_ticket.php`
   - Uncomment email notification lines
   - Configure SMTP settings or use PHP mail() function
   - Update support email address

## File Structure

```
it-support-pwa/
├── index.php              # Main application file
├── submit_ticket.php      # Ticket submission handler
├── styles.css             # Application styles
├── manifest.json          # PWA manifest
├── sw.js                  # Service worker
├── zopollo-logo.svg       # Main logo
├── icon-192.svg           # PWA icon 192x192
├── icon-512.svg           # PWA icon 512x512
├── .htaccess              # Apache configuration
├── tickets.json           # Ticket storage (auto-generated)
└── README.md              # This file
```

## Usage

### Accessing the App

1. Open your browser and navigate to your domain
2. The app will load with light mode by default
3. Click the theme toggle button to switch between light/dark modes

### Installing as PWA

**On Mobile (Android/iOS):**
1. Open the app in your mobile browser
2. Tap the install prompt that appears
3. Or use browser menu > "Add to Home Screen"

**On Desktop (Chrome/Edge):**
1. Click the install icon in the address bar
2. Or use browser menu > "Install Zopollo IT Support"

### Submitting Support Tickets

1. Navigate to "Submit Ticket" section
2. Fill in all required fields:
   - Full Name
   - Email Address
   - Phone Number (optional)
   - Priority Level
   - Issue Category
   - Subject
   - Issue Description
3. Click "Submit Ticket"
4. You'll receive a ticket ID for reference

### Priority Response Times

- **Critical**: Within 1 hour (Service down)
- **High**: Within 4 hours (System impacting)
- **Medium**: Within 24 hours (Non-critical issue)
- **Low**: Within 48 hours (General inquiry)

## Customization

### Update Contact Information

Edit `index.php` and update:
- Phone number: Search for `+1 (234) 567-890`
- Email: Search for `support@zopollo.com`
- Address: Update in the contact section

### Modify Theme Colors

Edit `styles.css` and update CSS variables:
```css
:root {
    --accent-primary: #0066ff;
    --accent-secondary: #00f0ff;
    /* Add more customizations */
}
```

### Configure Database (Advanced)

For production use, replace file-based storage with database:

1. Create MySQL database and table:
```sql
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id VARCHAR(50) UNIQUE,
    timestamp DATETIME,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    priority ENUM('low', 'medium', 'high', 'critical'),
    category VARCHAR(50),
    subject VARCHAR(255),
    message TEXT,
    status VARCHAR(50),
    ip_address VARCHAR(50)
);
```

2. Update `submit_ticket.php` to use database instead of JSON file

## Security Considerations

1. **HTTPS**: Always use HTTPS in production
2. **Input Validation**: All user inputs are sanitized
3. **Email Configuration**: Configure proper SMTP authentication
4. **File Permissions**: Restrict write access to necessary files only
5. **Database**: Use prepared statements to prevent SQL injection
6. **Rate Limiting**: Implement rate limiting to prevent spam

## Browser Support

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support (iOS 11.3+)
- Opera: Full support

## Troubleshooting

### PWA Not Installing
- Ensure you're using HTTPS (required for PWA)
- Check that `manifest.json` is accessible
- Verify service worker is registered (check browser console)

### Service Worker Not Working
- Clear browser cache
- Check browser console for errors
- Ensure `sw.js` is in the root directory

### Tickets Not Saving
- Check file permissions on `tickets.json`
- Ensure PHP has write access to the directory
- Check PHP error logs

### Theme Not Persisting
- Enable localStorage in browser
- Check browser privacy settings
- Clear browser cache and try again

## Development

### Local Testing

1. Install PHP development server:
   ```bash
   php -S localhost:8000
   ```

2. Access at: `http://localhost:8000`

### Testing PWA Features

1. Use Chrome DevTools > Application tab
2. Check Manifest, Service Workers, and Storage
3. Test offline mode by disabling network in DevTools

## Support

For technical support or questions:
- Email: support@zopollo.com
- Phone: +1 (234) 567-890
- Hours: 24/7 Emergency Support Available

## License

Copyright © 2025 Zopollo IT Solutions. All rights reserved.

## Version

Version 1.0.0 - Initial Release
