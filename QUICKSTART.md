# Quick Start Guide - Zopollo IT Support PWA

## Get Running in 2 Minutes! 🚀

### For Local Testing (Windows)

1. **Open Command Prompt or PowerShell**
   ```cmd
   cd C:\Temp\Web\it-support-pwa
   ```

2. **Start PHP Server**
   ```cmd
   php -S localhost:8000
   ```

3. **Open Browser**
   - Navigate to: http://localhost:8000
   - You should see the Zopollo IT Support homepage

4. **Test the App**
   - Click hamburger menu to see navigation
   - Toggle dark/light mode
   - Submit a test ticket
   - View tickets at: http://localhost:8000/view_tickets.php

### What You Get

✅ **Progressive Web App** - Installable on mobile and desktop
✅ **Dark/Light Mode** - Automatic theme switching
✅ **Offline Support** - Works without internet (after first visit)
✅ **IT Ticketing System** - Complete support ticket management
✅ **Responsive Design** - Perfect on all screen sizes
✅ **Modern UI** - Beautiful Zopollo branding with gradients

### Key Files

| File | Purpose |
|------|---------|
| [index.php](index.php) | Main application page |
| [submit_ticket.php](submit_ticket.php) | Handles ticket submissions |
| [view_tickets.php](view_tickets.php) | Admin ticket dashboard |
| [test.html](test.html) | PWA testing page |
| [styles.css](styles.css) | All your styling (dark/light modes) |
| [manifest.json](manifest.json) | PWA configuration |
| [sw.js](sw.js) | Service worker (offline support) |

### Testing PWA Features

Visit the test page to check PWA status:
```
http://localhost:8000/test.html
```

This will show:
- ✅ Service Worker status
- ✅ PWA support
- ✅ Manifest availability
- ✅ Offline caching status
- ✅ HTTPS status

### App Sections

1. **Home** - Hero section with overview
2. **Services** - IT services showcase (6 services)
3. **Submit Ticket** - Support ticket form with:
   - Priority levels (Critical/High/Medium/Low)
   - Categories (Hardware/Software/Network/Security/Email/Account)
   - Full contact details
4. **System Status** - Real-time status dashboard
5. **Knowledge Base** - IT documentation access
6. **Contact** - Contact information and support hours

### Submit Your First Ticket

1. Go to "Submit Ticket" section
2. Fill in:
   - Name: John Doe
   - Email: test@example.com
   - Priority: High
   - Category: Software
   - Subject: Test Ticket
   - Message: This is a test
3. Click "Submit Ticket"
4. You'll receive a ticket ID (e.g., TICK-67C8F9A1)

### View Submitted Tickets

Navigate to: http://localhost:8000/view_tickets.php

You'll see:
- Total tickets dashboard
- Priority breakdown
- All ticket details
- Ticket IDs and timestamps

### Installing as PWA

**Note:** Full PWA installation requires HTTPS. For local testing:

1. Visit the site
2. Look for install prompt (may not appear on localhost)
3. In Chrome: Menu > Install Zopollo IT Support
4. App will open in standalone window

### Customization Quick Tips

**Change Colors:**
Edit [styles.css](styles.css) lines 1-30:
```css
:root {
    --accent-primary: #0066ff;    /* Change this */
    --accent-secondary: #00f0ff;  /* And this */
}
```

**Update Contact Info:**
Edit [index.php](index.php):
- Search for: `+1 (234) 567-890`
- Replace with your phone
- Search for: `support@zopollo.com`
- Replace with your email

**Enable Email Notifications:**
Edit [submit_ticket.php](submit_ticket.php):
- Uncomment lines ~100 and ~130
- Configure your email settings

### File Structure
```
it-support-pwa/
├── 📄 index.php              Main app
├── 📄 submit_ticket.php      Ticket handler
├── 📄 view_tickets.php       Admin dashboard
├── 📄 test.html              PWA test page
├── 🎨 styles.css             All styling
├── ⚙️ manifest.json          PWA config
├── ⚙️ sw.js                  Service worker
├── 🖼️ zopollo-logo.svg       Main logo
├── 🖼️ icon-192.svg           App icon (192px)
├── 🖼️ icon-512.svg           App icon (512px)
├── 🖼️ favicon.svg            Browser favicon
├── 📋 .htaccess              Apache config
├── 📋 config.example.php     Config template
├── 📖 README.md              Full docs
├── 📖 INSTALLATION.md        Install guide
└── 📖 QUICKSTART.md          This file
```

### Common Tasks

**Start Server:**
```bash
php -S localhost:8000
```

**View in Browser:**
```
http://localhost:8000
```

**Test PWA:**
```
http://localhost:8000/test.html
```

**View Tickets:**
```
http://localhost:8000/view_tickets.php
```

**Stop Server:**
Press `Ctrl+C` in terminal

### Next Steps

1. ✅ Test the app locally
2. ✅ Submit test tickets
3. ✅ Customize branding
4. ✅ Update contact information
5. ✅ Read [INSTALLATION.md](INSTALLATION.md) for production deployment
6. ✅ Configure email notifications
7. ✅ Set up HTTPS for production
8. ✅ Deploy to web server

### Troubleshooting

**Problem:** PHP server won't start
**Solution:** Check if port 8000 is in use. Try: `php -S localhost:8080`

**Problem:** Can't submit tickets
**Solution:** Check file permissions. The directory needs write access.

**Problem:** Styles not loading
**Solution:** Verify [styles.css](styles.css) is in the same directory.

**Problem:** PWA features not working
**Solution:** PWA requires HTTPS. Use localhost for testing or deploy to HTTPS server.

### Features Included

✅ Responsive navigation with overlay menu
✅ Dark/light mode toggle (persists in localStorage)
✅ PWA install prompt for mobile/desktop
✅ Service worker for offline support
✅ Support ticket submission system
✅ Ticket priority levels (4 levels)
✅ 7 ticket categories
✅ Admin ticket viewer with statistics
✅ System status dashboard
✅ Knowledge base section
✅ Contact information page
✅ Smooth scrolling navigation
✅ Form validation
✅ Responsive design (mobile-first)
✅ Loading animations
✅ Gradient branding matching Zopollo style
✅ SVG logos for crisp display

### Browser Support

✅ Chrome 67+
✅ Edge 79+
✅ Firefox 63+
✅ Safari 11.1+
✅ Opera 54+

### Production Deployment

When ready for production, see [INSTALLATION.md](INSTALLATION.md) for:
- Apache/Nginx configuration
- HTTPS setup with Let's Encrypt
- Database configuration
- Email SMTP setup
- Security hardening
- Performance optimization

---

## You're All Set! 🎉

Your Zopollo IT Support PWA is ready to use. Start the PHP server and open http://localhost:8000 to see it in action!

For production deployment and advanced configuration, check out [INSTALLATION.md](INSTALLATION.md).

**Need Help?**
📧 support@zopollo.com
📱 +1 (234) 567-890
