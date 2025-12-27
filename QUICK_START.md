# Quick Start Guide - Notifications

## 📱 Two Notification Systems

### 1. In-App Notifications (All Devices - Including iOS!)
✅ **Works on iPhone/iOS!**
- Receive notifications while the PWA is open
- Beautiful slide-in notifications
- Click to open specific tickets
- Checks for updates every 30 seconds
- **No setup required** - works immediately!

### 2. Push Notifications (Android Only)
- Receive notifications even when app is closed
- Requires composer installation

## 🚀 Get Started in 3 Steps

### Step 1: Install Dependencies

Open Command Prompt in the project directory and run:

```bash
cd "C:\Temp\Web\Support 2.0\Support-2.0"
composer install
```

**Don't have Composer?** Download it from: https://getcomposer.org/download/

### Step 2: Test the Setup

Visit this URL to send a test notification:
```
http://your-domain/send_test_notification.php?ticket_id=TEST-123
```

### Step 3: Use on Mobile

1. Open the PWA on your mobile device
2. Wait for notification permission prompt (5 seconds)
3. Grant permission
4. Done! You'll now receive notifications when tickets are updated

## ✅ What's Already Configured

- ✅ VAPID keys set up
- ✅ All JavaScript files updated with your public key
- ✅ Backend notification system ready
- ✅ Service worker configured
- ✅ Test utilities ready

## 📱 How It Works

1. User visits PWA on mobile → Gets permission prompt
2. User grants permission → Device subscribed
3. Ticket updated → Notification sent automatically
4. User clicks notification → Opens specific ticket

## 🔧 Files You Can Customize

- **[config.php](config.php)** - Your VAPID keys (already set)
- **[sw.js](sw.js)** - Service worker behavior
- **[update_ticket.php](update_ticket.php:184-306)** - When notifications are sent

## 📖 Full Documentation

- **[IN_APP_NOTIFICATIONS.md](IN_APP_NOTIFICATIONS.md)** - In-app notification system (iOS compatible!)
- **[SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md)** - Push notification setup (Android)
- **[NOTIFICATIONS_README.md](NOTIFICATIONS_README.md)** - Complete technical documentation

## 🆘 Common Issues

**"Web-push library not installed"**
→ Run `composer install`

**"Permission denied"**
→ User needs to grant notification permission on mobile

**"No subscriptions found"**
→ User hasn't visited PWA or granted permission yet

---

**That's it!** Just run `composer install` and you're ready to go! 🎉
