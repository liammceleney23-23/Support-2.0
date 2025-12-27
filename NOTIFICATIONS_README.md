# Push Notifications Setup Guide

## Overview

The IT Support PWA now supports push notifications for mobile devices. Users will receive notifications when:
- A new response is added to their ticket
- The status of their ticket changes

## How It Works

### 1. Service Worker (sw.js)

The service worker handles:
- Caching for offline support
- Push notification events
- Notification click handling (opens the relevant ticket)
- Background sync for offline scenarios

### 2. Notification Permission Request

- **Mobile Only**: Notifications are only requested on mobile devices (detected via user agent and screen width)
- **Delayed Request**: Permission is requested 5 seconds after page load to avoid overwhelming new users
- **Automatic Subscription**: Once permission is granted, the user is automatically subscribed to push notifications

### 3. Subscription Storage

When a user grants notification permission:
- A subscription object is created by the browser's Push API
- The subscription is sent to `save_subscription.php`
- Subscriptions are stored in `subscriptions.json`
- Each subscription includes: endpoint, keys, timestamp, IP address, and user agent

### 4. Notification Triggering

Notifications are automatically sent when:
- Support team adds a response to a ticket (`update_ticket.php` - action: `add_response`)
- Ticket status is changed (`update_ticket.php` - action: `update_status`)

### 5. Notification Click Behavior

When a user clicks on a notification:
- If a browser window is already open to the ticket view, it focuses that window
- Otherwise, it opens a new window directly to the specific ticket (`manage_ticket.php?id=TICKET_ID`)

## Files Modified/Created

### Created Files:
- **sw.js** - Service worker with notification handlers
- **save_subscription.php** - Saves push notification subscriptions
- **send_test_notification.php** - Test script for sending notifications
- **NOTIFICATIONS_README.md** - This documentation

### Modified Files:
- **index.php** - Added service worker registration and notification permission request
- **view_tickets.php** - Added service worker registration
- **manage_ticket.php** - Added service worker registration
- **update_ticket.php** - Added notification triggering on ticket updates

## Current Implementation Status

### ✅ Implemented:
- Service worker with push notification event handlers
- Notification permission request (mobile only)
- Subscription management
- Notification triggering logic
- Click handling to open specific tickets
- Logging system for notification attempts

### ⚠️ Requires Additional Setup for Production:

The current implementation includes all the frontend code and backend structure, but actual push notification delivery requires:

#### 1. Install Web Push Library

```bash
composer require minishlink/web-push
```

#### 2. Generate VAPID Keys

```bash
vendor/bin/web-push generate-vapid-keys
```

This will output something like:
```
Public Key: BDd3_...
Private Key: XYZ123...
```

#### 3. Store Keys Securely

Create a `config.php` file:

```php
<?php
return [
    'vapid' => [
        'subject' => 'mailto:support@zopollo.com',
        'publicKey' => 'YOUR_PUBLIC_KEY_HERE',
        'privateKey' => 'YOUR_PRIVATE_KEY_HERE'
    ]
];
?>
```

#### 4. Update JavaScript Files

In `index.php`, `view_tickets.php`, and `manage_ticket.php`, replace:

```javascript
applicationServerKey: null
```

With:

```javascript
applicationServerKey: urlBase64ToUint8Array('YOUR_PUBLIC_VAPID_KEY_HERE')
```

Add this helper function:

```javascript
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
```

#### 5. Update update_ticket.php

Uncomment and configure the web-push code in the `sendPushNotification()` function:

```php
require_once 'vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$config = require 'config.php';

$auth = [
    'VAPID' => $config['vapid']
];

$webPush = new WebPush($auth);

foreach ($subscriptions as $subscription) {
    $webPush->queueNotification(
        Subscription::create($subscription),
        $payload
    );
}

foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        // Success - notification sent
    } else {
        // Failed - handle error
        // Consider removing invalid subscriptions
    }
}
```

## Testing

### 1. Test on Mobile Device

1. Open the PWA on a mobile device
2. Wait 5 seconds for the notification permission prompt
3. Grant notification permission
4. Check browser console for "Push subscription successful"

### 2. Verify Subscription

Check that `subscriptions.json` file is created and contains your subscription.

### 3. Test Notification Triggering

1. Submit a test ticket
2. Go to `manage_ticket.php?id=YOUR_TICKET_ID`
3. Add a response or change the status
4. Check `notifications.log` for notification attempt

### 4. Manual Test (After Production Setup)

Access: `send_test_notification.php?ticket_id=TICK-123456`

This will attempt to send a test notification to all subscribed devices.

## Browser Compatibility

### Fully Supported:
- Chrome/Edge (Android)
- Firefox (Android)
- Samsung Internet
- Opera (Android)

### Limited/No Support:
- iOS Safari (Push API not supported)
- iOS Chrome (Uses Safari engine, not supported)
- iOS Firefox (Uses Safari engine, not supported)

**Note:** iOS devices will not be able to receive push notifications due to Apple's limitations. The code gracefully handles this by checking for API availability.

## Privacy & Security Considerations

1. **User Consent**: Notifications are only sent to users who explicitly grant permission
2. **Mobile Only**: Desktop users are not prompted for notifications
3. **Secure Transmission**: Use HTTPS in production for secure Push API communication
4. **Subscription Privacy**: Subscriptions are stored server-side and not exposed to other users
5. **VAPID Authentication**: Ensures only your server can send notifications to your users

## Troubleshooting

### Users Not Receiving Notifications

1. Check browser console for errors
2. Verify `subscriptions.json` contains active subscriptions
3. Check `notifications.log` for notification attempts
4. Ensure VAPID keys are correctly configured
5. Verify HTTPS is enabled (required for Push API)
6. Test on a supported browser/device

### Permission Request Not Showing

1. Ensure device is detected as mobile
2. Check if permission was previously denied (stored in browser)
3. Verify service worker is registered successfully
4. Check browser console for errors

### Notifications Not Clickable

1. Verify service worker is active
2. Check notification click handler in `sw.js`
3. Ensure URLs in notification payload are correct

## Future Enhancements

Potential improvements for the notification system:

1. **Notification Preferences**: Allow users to choose which events trigger notifications
2. **Quiet Hours**: Don't send notifications during specified times
3. **Subscription Management UI**: Allow users to view and manage their devices
4. **Rich Notifications**: Include images, action buttons, or more details
5. **Email Fallback**: Send email if push notification fails
6. **Notification History**: Keep track of sent notifications
7. **Batching**: Group multiple updates into a single notification
8. **Priority-Based**: Critical tickets send urgent notifications with different sounds/vibrations

## Support

For issues or questions about the notification system, refer to:
- Browser Push API documentation
- Web Push library documentation: https://github.com/web-push-libs/web-push-php
- Service Worker API documentation

---

**Last Updated**: 2025-12-27
