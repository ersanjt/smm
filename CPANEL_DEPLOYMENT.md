# 🚀 راهنمای Deploy روی cPanel + VPS

## 📋 **مراحل آماده‌سازی:**

### 1️⃣ **آماده‌سازی فایل‌ها:**
```bash
# فایل‌های مورد نیاز برای cPanel:
- index.php (اصلی)
- application/ (پوشه اصلی)
- system/ (CodeIgniter core)
- .htaccess (URL rewriting)
- sql_esjdev_boostpanel.sql (دیتابیس)
```

### 2️⃣ **تنظیمات cPanel:**

#### **A. ایجاد دیتابیس:**
1. وارد **MySQL Databases** شوید
2. **Database Name:** `smm_turk_db` (یا نام دلخواه)
3. **Database User:** `smm_turk_user` (یا نام دلخواه)
4. **Password:** رمز قوی انتخاب کنید
5. **User را به Database اضافه کنید**

#### **B. آپلود فایل‌ها:**
1. **File Manager** را باز کنید
2. به پوشه **public_html** بروید
3. تمام فایل‌های پروژه را آپلود کنید
4. **Permissions** را روی **755** تنظیم کنید

#### **C. تنظیمات Database:**
1. فایل `application/config/database.php` را ویرایش کنید:
```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'smm_turk_user',
    'password' => 'your_password',
    'database' => 'smm_turk_db',
    'dbdriver' => 'mysqli',
    // ... باقی تنظیمات
);
```

### 3️⃣ **Import دیتابیس:**
1. **phpMyAdmin** را باز کنید
2. دیتابیس جدید را انتخاب کنید
3. **Import** کلیک کنید
4. فایل `sql_esjdev_boostpanel.sql` را انتخاب کنید
5. **Go** کلیک کنید

### 4️⃣ **تنظیمات امنیتی:**
1. **File Permissions:**
   - `application/logs/` → **777**
   - `application/sessions/` → **777**
   - `application/cache/` → **777**

2. **.htaccess** تنظیمات:
   - URL rewriting فعال
   - Security headers
   - Hide sensitive files

### 5️⃣ **تست نهایی:**
1. **Domain:** `https://smm-turk.com`
2. **Admin Panel:** `https://smm-turk.com/panel`
3. **Database Connection:** تست کنید
4. **File Uploads:** تست کنید

## 🔧 **تنظیمات پیشرفته:**

### **PHP Version:**
- **PHP 7.4+** یا **PHP 8.0+** انتخاب کنید
- **Extensions:** mysqli, curl, gd, mbstring

### **SSL Certificate:**
- **Let's Encrypt** یا **Comodo SSL**
- **Force HTTPS** در .htaccess

### **Email Settings:**
- **SMTP** تنظیمات در `application/config/`
- **Email templates** در `application/views/`

## 🚨 **نکات مهم:**

1. **Backup** قبل از deploy
2. **Database credentials** را محفوظ کنید
3. **File permissions** را درست تنظیم کنید
4. **SSL certificate** فعال کنید
5. **Error logging** را فعال کنید

## 📞 **پشتیبانی:**
- **Logs:** `application/logs/`
- **Error Pages:** `application/views/errors/`
- **Database:** phpMyAdmin
- **File Manager:** cPanel File Manager

---
**🎯 پروژه شما آماده deploy روی smm-turk.com است!**
