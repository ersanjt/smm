# 🚀 راهنمای کامل نصب SMM Panel روی cPanel

## 📋 پیش‌نیازها
- ✅ هاست cPanel با PHP 7.4 یا بالاتر
- ✅ MySQL 5.7 یا بالاتر  
- ✅ دسترسی به phpMyAdmin
- ✅ SSL Certificate (اختیاری اما توصیه می‌شود)

## 📦 فایل‌های مورد نیاز
- `smm_panel_cpanel_final.zip` - فایل اصلی برنامه
- `database/smm_turk_database.sql` - فایل دیتابیس

---

## 🔧 مراحل نصب

### مرحله 1: آپلود فایل‌ها
1. وارد **cPanel** شوید
2. به بخش **File Manager** بروید
3. وارد پوشه `public_html` شوید
4. فایل `smm_panel_cpanel_final.zip` را آپلود کنید
5. روی فایل zip کلیک راست کرده و **Extract** کنید
6. فایل zip را حذف کنید

### مرحله 2: ایجاد دیتابیس MySQL
1. در cPanel، به بخش **MySQL Databases** بروید
2. یک دیتابیس جدید ایجاد کنید:
   - نام دیتابیس: `yourusername_smmturk` (yourusername را با نام کاربری cPanel خود جایگزین کنید)
3. یک کاربر MySQL ایجاد کنید:
   - نام کاربری: `yourusername_smmuser`
   - رمز عبور: رمز قوی انتخاب کنید
4. کاربر را به دیتابیس اضافه کنید و **ALL PRIVILEGES** را بدهید

### مرحله 3: وارد کردن دیتابیس
1. به **phpMyAdmin** بروید
2. دیتابیس خود را انتخاب کنید
3. روی تب **Import** کلیک کنید
4. فایل `database/smm_turk_database.sql` را انتخاب کنید
5. روی **Go** کلیک کنید

### مرحله 4: تنظیم فایل‌های پیکربندی

#### تنظیم دیتابیس (`application/config/database.php`):
```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'yourusername_smmuser',  // نام کاربری MySQL شما
    'password' => 'your_password',         // رمز عبور MySQL شما  
    'database' => 'yourusername_smmturk', // نام دیتابیس شما
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### تنظیم URL (`application/config/config.php`):
```php
$config['base_url'] = 'https://yourdomain.com/';  // دامنه شما
$config['encryption_key'] = 'your_random_key_here';  // کلید تصادفی
$config['security_token'] = 'your_security_token_here';  // توکن امنیتی
```

### مرحله 5: تنظیم مجوزهای فایل
در File Manager، مجوزهای زیر را تنظیم کنید:
- پوشه `application/logs`: **755**
- پوشه `application/cache`: **755**  
- پوشه `application/sessions`: **755**

### مرحله 6: تنظیم Cron Jobs
1. در cPanel، به بخش **Cron Jobs** بروید
2. cron job زیر را اضافه کنید:
```
*/5 * * * * /usr/bin/php /home/yourusername/public_html/application/controllers/Cron.php
```

---

## 🧪 تست نصب

### بررسی صفحات اصلی:
- ✅ صفحه اصلی: `https://yourdomain.com/public/`
- ✅ صفحه ورود: `https://yourdomain.com/public/login.html`
- ✅ صفحه ثبت‌نام: `https://yourdomain.com/public/register.html`
- ✅ پنل ادمین: `https://yourdomain.com/public/admin/`

### بررسی عملکرد:
- ✅ اتصال به دیتابیس
- ✅ بارگذاری صفحات
- ✅ عملکرد فرم‌ها

---

## 🔒 تنظیمات امنیتی

### فعال‌سازی SSL:
1. در cPanel، به بخش **SSL/TLS** بروید
2. گواهی SSL را فعال کنید
3. در فایل `config.php`، `base_url` را به `https://` تغییر دهید

### تنظیمات اضافی:
- فایل‌های حساس را از دسترسی عمومی محافظت کنید
- رمزهای عبور قوی استفاده کنید
- به‌روزرسانی‌های امنیتی را پیگیری کنید

---

## 🐛 عیب‌یابی

### خطای 500 (Internal Server Error):
1. مجوزهای فایل‌ها را بررسی کنید
2. فایل `application/logs/` را بررسی کنید
3. تنظیمات PHP را بررسی کنید

### خطای اتصال به دیتابیس:
1. اطلاعات دیتابیس را در `database.php` بررسی کنید
2. اطمینان حاصل کنید که کاربر MySQL دسترسی کامل دارد
3. نام دیتابیس و کاربر را با پیشوند cPanel بررسی کنید

### خطای 404 (Not Found):
1. فایل `.htaccess` را بررسی کنید
2. تنظیمات mod_rewrite را فعال کنید
3. مسیر فایل‌ها را بررسی کنید

### مشکلات عملکرد:
1. کش PHP را پاک کنید
2. فایل‌های log را بررسی کنید
3. منابع سرور را بررسی کنید

---

## 📞 پشتیبانی

### فایل‌های مفید برای عیب‌یابی:
- `application/logs/` - فایل‌های log
- `application/cache/` - فایل‌های کش
- Error logs در cPanel

### اطلاعات مورد نیاز برای پشتیبانی:
- نسخه PHP
- نسخه MySQL  
- پیام‌های خطا
- فایل‌های log

---

## 🎉 تبریک!

پنل SMM شما با موفقیت نصب شد! 

**نکات مهم:**
- همیشه از فایل‌های خود پشتیبان تهیه کنید
- به‌روزرسانی‌های امنیتی را پیگیری کنید
- رمزهای عبور قوی استفاده کنید
- عملکرد سرور را مانیتور کنید

**موفق باشید! 🚀**
