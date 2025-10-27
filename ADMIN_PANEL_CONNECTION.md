# ارتباط بین پنل ادمین و پنل کاربری

## ✅ وضعیت اتصال

### 1. **Session Management مشترک**
```
✅ دیتابیس: users, sessions
✅ Cookie: Shared across panels
✅ Authentication: Centralized in Auth.php
```

### 2. **Navigation بین پنل‌ها**
```html
✅ Admin → User Panel: ../panel/index.html
✅ User → Admin Panel: ../admin/index.html
✅ Profile Dropdown: Switch Panel
```

### 3. **Authentication Flow**
```
Login (login.html)
    ↓
Auth.php Controller
    ↓
Check User Role
    ↓
Redirect:
    - Admin → admin/index.html
    - User → panel/index.html
```

### 4. **Shared Features**
```javascript
✅ Theme Toggle (Dark/Light Mode)
✅ Profile Management
✅ Logout Function
✅ Session Persistence
```

## 🔗 لینک‌های مهم

### Admin Panel → User Panel
```html
<!-- Sidebar Link -->
<a href="../panel/index.html">User Panel</a>

<!-- Profile Dropdown -->
<a href="#" onclick="window.location.href='../panel/index.html'">
    Switch to User Panel
</a>
```

### User Panel → Admin Panel
```html
<!-- Add to User Panel -->
<a href="../admin/index.html">Admin Panel</a>
```

## 📊 API Endpoints مشترک

### Admin API
```php
✅ /api/admin/stats
✅ /api/admin/users
✅ /api/admin/orders
✅ /api/admin/services
✅ /api/admin/tickets
✅ /api/admin/update_user
✅ /api/admin/update_order
✅ /api/admin/update_service
```

### User API
```php
✅ /api/auth/register
✅ /api/auth/login
✅ /api/auth/logout
✅ /api/services
✅ /api/tickets
```

## 🎯 ویژگی‌های Implement شده

### در Admin Panel
```javascript
✅ toggleTheme() - تغییر تم
✅ logout() - خروج از سیستم
✅ toggleProfileMenu() - نمایش منوی پروفایل
✅ viewProfile() - مشاهده پروفایل
✅ switchTab() - تغییر تب
✅ initTheme() - بارگذاری تم از localStorage
```

### Theme Synchronization
```javascript
✅ localStorage.setItem('adminTheme', theme);
✅ localStorage.getItem('adminTheme')
✅ Shared between panels
```

## 🔐 امنیت

### Session Check
```php
✅ Check logged in status
✅ Verify user role
✅ Prevent unauthorized access
✅ CSRF Protection
```

## 🚀 نحوه استفاده

### 1. ورود به Admin Panel
```
URL: http://localhost:8000/public/admin/index.html
- کلیک روی "Switch to User Panel" برای رفتن به پنل کاربری
```

### 2. ورود به User Panel
```
URL: http://localhost:8000/public/panel/index.html
- لینک برای رفتن به پنل ادمین (در صورت نیاز)
```

### 3. تعویض تم
```
1. کلیک روی دکمه ماه/خورشید
2. انتخاب در localStorage ذخیره می‌شود
3. در تمام صفحات پنل کاربری/ادمین اعمال می‌شود
```

## 📋 TODO

- [ ] افزودن Middleware برای Admin Access Control
- [ ] مدیریت Permission ها
- [ ] لاگ‌گیری فعالیت‌های ادمین
- [ ] Backup/Restore تنظیمات
