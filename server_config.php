<?php
// تنظیمات مخصوص سرور smm-turk.com
// IP: 92.205.182.143

// تنظیمات سرور
define('SERVER_IP', '92.205.182.143');
define('DOMAIN', 'smm-turk.com');
define('BASE_URL', 'https://smm-turk.com');

// تنظیمات دیتابیس (تغییر دهید)
$database_config = [
    'hostname' => '92.205.182.143', // یا localhost
    'username' => 'smm_turk_user', // نام کاربری دیتابیس
    'password' => 'your_strong_password', // رمز دیتابیس
    'database' => 'smm_turk_db', // نام دیتابیس
    'port' => 3306, // پورت MySQL
];

// تنظیمات امنیتی
$security_config = [
    'encryption_key' => 'your_32_character_encryption_key_here',
    'session_lifetime' => 7200, // 2 ساعت
    'max_login_attempts' => 5,
    'lockout_duration' => 900, // 15 دقیقه
];

// تنظیمات email
$email_config = [
    'smtp_host' => 'mail.smm-turk.com',
    'smtp_port' => 587,
    'smtp_username' => 'noreply@smm-turk.com',
    'smtp_password' => 'email_password',
    'from_email' => 'noreply@smm-turk.com',
    'from_name' => 'SMM Turk Panel',
];

// تنظیمات payment
$payment_config = [
    'stripe_public_key' => 'pk_test_...',
    'stripe_secret_key' => 'sk_test_...',
    'paypal_client_id' => 'your_paypal_client_id',
    'paypal_secret' => 'your_paypal_secret',
];

echo "<h2>🚀 تنظیمات سرور smm-turk.com</h2>";
echo "<p><strong>IP:</strong> " . SERVER_IP . "</p>";
echo "<p><strong>Domain:</strong> " . DOMAIN . "</p>";
echo "<p><strong>Base URL:</strong> " . BASE_URL . "</p>";

echo "<h3>📋 مراحل deploy:</h3>";
echo "<ol>";
echo "<li>فایل‌ها را به public_html آپلود کنید</li>";
echo "<li>دیتابیس MySQL ایجاد کنید</li>";
echo "<li>فایل sql_esjdev_boostpanel.sql را import کنید</li>";
echo "<li>تنظیمات database.php را ویرایش کنید</li>";
echo "<li>Permissions فایل‌ها را تنظیم کنید</li>";
echo "<li>SSL certificate فعال کنید</li>";
echo "<li>تست نهایی انجام دهید</li>";
echo "</ol>";

echo "<h3>🔧 فایل‌های مهم:</h3>";
echo "<ul>";
echo "<li>index.php (اصلی)</li>";
echo "<li>application/config/database.php</li>";
echo "<li>application/config/config.php</li>";
echo "<li>.htaccess</li>";
echo "<li>sql_esjdev_boostpanel.sql</li>";
echo "</ul>";
?>
