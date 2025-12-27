# Navigation Guide - Desktop vs Mobile

## 🖥️ Desktop View (Screen Width > 768px)

### Header Layout
```
┌─────────────────────────────────────────────────────────────┐
│ [Logo]  🏠Home  🛠️Services  🎫Ticket  📊Status  📚Knowledge  📧Contact     [🌙Dark] │
└─────────────────────────────────────────────────────────────┘
```

### Features
- **Logo:** Compact Zopollo logo (85px) on left
- **Navigation Bar:** Centered, horizontal menu with all sections
- **Theme Toggle:** Right side of header
- **No Hamburger Menu:** Clean, professional look
- **No PWA Install Prompt:** Desktop users don't see installation banner

### Navigation Behavior
- Click any menu item to scroll smoothly to that section
- Hover effects with gradient underline animation
- Icons + text for clarity
- Always visible at top of page (sticky header)

---

## 📱 Mobile View (Screen Width ≤ 768px)

### Header Layout
```
┌───────────────────────────────┐
│ [☰] [Logo]          [🌙Dark] │
└───────────────────────────────┘
```

### Features
- **Hamburger Icon:** Left side to open full-screen menu
- **Logo:** Compact Zopollo logo (70px) in center
- **Theme Toggle:** Right side of header
- **No Desktop Nav:** Space-efficient mobile design
- **PWA Install Prompt:** Shows at bottom when available

### Navigation Behavior
- Tap hamburger (☰) to open overlay menu
- Full-screen menu slides down from top
- Large, touch-friendly buttons
- Close button (×) in top-right
- Tap outside menu to close

### Mobile Menu Layout
```
┌─────────────────────────────────┐
│  [Logo]                    [×]  │
├─────────────────────────────────┤
│                                 │
│      🏠  Home                   │
│                                 │
│      🛠️  Services               │
│                                 │
│      🎫  Submit Ticket          │
│                                 │
│      📊  System Status          │
│                                 │
│      📚  Knowledge Base         │
│                                 │
│      📧  Contact Us             │
│                                 │
├─────────────────────────────────┤
│  24/7 Support Hotline          │
│  +1 (234) 567-890              │
│  support@zopollo.com           │
└─────────────────────────────────┘
```

### PWA Install Prompt (Mobile Only)
```
┌─────────────────────────────────────────┐
│ 📱 Install Zopollo IT Support          │
│    Get quick access to support services │
│                                         │
│    [Install]        [Not Now]          │
└─────────────────────────────────────────┘
```

---

## 🎯 Responsive Breakpoint

**768px** is the magic number:
- **≤ 768px:** Mobile layout (hamburger menu)
- **> 768px:** Desktop layout (navigation bar)

---

## 🎨 Visual Comparison

### Desktop Header
- **Width:** Full width, 3-section layout
- **Logo Position:** Left
- **Navigation:** Center (horizontal)
- **Actions:** Right
- **Height:** ~80px

### Mobile Header
- **Width:** Full width, simple 2-section layout
- **Logo Position:** Center-left
- **Navigation:** Hidden (behind hamburger)
- **Actions:** Right
- **Height:** ~60px

---

## 💡 User Experience

### Why Different Navigation?

**Desktop:**
- More screen space available
- Mouse/trackpad precision
- Users expect traditional navigation bar
- No need for space-saving hamburger

**Mobile:**
- Limited screen space
- Touch-based interaction
- Hamburger menu is mobile standard
- Large touch targets needed
- PWA installation relevant for mobile users

---

## 🧪 Testing Guide

### Test Desktop Navigation
1. Open site on desktop (or resize browser > 768px)
2. Verify navigation bar appears in header
3. Verify no hamburger menu visible
4. Hover over menu items - check animations
5. Click menu items - verify smooth scroll
6. Check no install prompt appears

### Test Mobile Navigation
1. Open site on mobile (or resize browser ≤ 768px)
2. Verify hamburger menu appears
3. Verify no desktop navigation visible
4. Tap hamburger - verify overlay opens
5. Tap menu items - verify smooth scroll and menu closes
6. Check install prompt appears (if PWA criteria met)

---

## 📋 Menu Items (Both Versions)

| Icon | Label | Destination | Description |
|------|-------|-------------|-------------|
| 🏠 | Home | #home | Hero section |
| 🛠️ | Services | #services | IT services grid |
| 🎫 | Submit Ticket | #support | Support form |
| 📊 | Status | #status | System status |
| 📚 | Knowledge | #knowledge | Knowledge base |
| 📧 | Contact | #contact | Contact info |

---

## 🔧 Customization

### Change Breakpoint
Edit `styles.css` line ~694:
```css
@media (max-width: 768px) {
```
Change `768px` to your preferred breakpoint.

### Adjust Desktop Nav Spacing
Edit `styles.css` line ~233:
```css
gap: 1.5rem;
```

### Modify Mobile Detection
Edit `index.php` line ~407:
```javascript
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        || window.innerWidth <= 768;
}
```

---

**Note:** Always test on actual devices, not just browser resize, for accurate mobile experience!
