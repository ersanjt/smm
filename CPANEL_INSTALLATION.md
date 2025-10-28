# راهنمای نصب SMM Panel روی cPanel

## پیش‌نیازها
- هاست cPanel با PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر
- دسترسی به phpMyAdmin

## مراحل نصب

### 1. آپلود فایل‌ها
1. فایل `smm_panel_cpanel.zip` را دانلود کنید
2. در cPanel، به بخش **File Manager** بروید
3. فایل zip را در پوشه `public_html` آپلود کنید
4. فایل zip را extract کنید

### 2. ایجاد دیتابیس
1. در cPanel، به بخش **MySQL Databases** بروید
2. یک دیتابیس جدید ایجاد کنید (مثلاً: `yourusername_smmturk`)
3. یک کاربر MySQL ایجاد کنید (مثلاً: `yourusername_smmuser`)
4. کاربر را به دیتابیس اضافه کنید و تمام دسترسی‌ها را بدهید

### 3. تنظیم فایل‌های پیکربندی

#### فایل `application/config/database.php`:
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

#### فایل `application/config/config.php`:
```php
$config['base_url'] = 'https://yourdomain.com/';  // دامنه شما
```

### 4. وارد کردن دیتابیس
1. در phpMyAdmin، دیتابیس خود را انتخاب کنید
2. فایل `database/smm_turk_database.sql` را import کنید

### 5. تنظیم مجوزهای فایل
در File Manager، مجوزهای زیر را تنظیم کنید:
- پوشه `application/logs`: 755
- پوشه `application/cache`: 755
- پوشه `application/sessions`: 755

### 6. تست نصب
1. به آدرس `https://yourdomain.com/public/` بروید
2. صفحه اصلی باید نمایش داده شود
3. برای ورود به پنل ادمین: `https://yourdomain.com/public/admin/`

## تنظیمات اضافی

### تنظیم Cron Jobs
در cPanel، به بخش **Cron Jobs** بروید و cron job زیر را اضافه کنید:
```
*/5 * * * * /usr/bin/php /home/yourusername/public_html/application/controllers/Cron.php
```

### تنظیم SSL
اگر SSL فعال نیست، در cPanel آن را فعال کنید.

## عیب‌یابی

### خطای 500
- مجوزهای فایل‌ها را بررسی کنید
- فایل `application/logs/` را بررسی کنید

### خطای اتصال به دیتابیس
- اطلاعات دیتابیس را در `application/config/database.php` بررسی کنید
- اطمینان حاصل کنید که کاربر MySQL دسترسی کامل دارد

### خطای 404
- فایل `.htaccess` را بررسی کنید
- تنظیمات mod_rewrite را فعال کنید

## پشتیبانی
در صورت بروز مشکل، فایل‌های log را بررسی کنید:
- `application/logs/`
- Error logs در cPanel
