<?php
// تست اتصال به سرور و دیتابیس
echo "<h2>🔍 تست اتصال سرور</h2>";

// تست IP سرور
echo "<p><strong>IP سرور:</strong> 92.205.182.143</p>";

// تست PHP
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// تست Extensions
$required_extensions = ['mysqli', 'curl', 'gd', 'mbstring', 'json'];
echo "<h3>📦 PHP Extensions:</h3>";
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

// تست اتصال MySQL
echo "<h3>🗄️ تست اتصال MySQL:</h3>";
try {
    $host = '92.205.182.143';
    $username = 'your_username'; // تغییر دهید
    $password = 'your_password'; // تغییر دهید
    $database = 'your_database'; // تغییر دهید
    
    $connection = new mysqli($host, $username, $password, $database);
    
    if ($connection->connect_error) {
        echo "<p>❌ خطا در اتصال: " . $connection->connect_error . "</p>";
    } else {
        echo "<p>✅ اتصال موفق!</p>";
        echo "<p><strong>Server Info:</strong> " . $connection->server_info . "</p>";
        $connection->close();
    }
} catch (Exception $e) {
    echo "<p>❌ خطا: " . $e->getMessage() . "</p>";
}

// تست فایل‌ها
echo "<h3>📁 تست فایل‌ها:</h3>";
$required_files = [
    'index.php',
    'application/config/database.php',
    'application/config/config.php',
    'system/core/CodeIgniter.php'
];

foreach ($required_files as $file) {
    $status = file_exists($file) ? '✅' : '❌';
    echo "<p>$status $file</p>";
}

// تست permissions
echo "<h3>🔐 تست Permissions:</h3>";
$writable_dirs = [
    'application/logs',
    'application/sessions',
    'application/cache'
];

foreach ($writable_dirs as $dir) {
    if (is_dir($dir)) {
        $status = is_writable($dir) ? '✅' : '❌';
        echo "<p>$status $dir (writable)</p>";
    } else {
        echo "<p>⚠️ $dir (not found)</p>";
    }
}

echo "<hr>";
echo "<p><strong>🎯 برای تست کامل، اطلاعات دیتابیس را در فایل وارد کنید و دوباره تست کنید.</strong></p>";
?>
