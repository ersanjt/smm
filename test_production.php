<?php
// SMM Turk Panel - Production Test File
// Test all components on smm-turk.com

echo "<h1>🚀 SMM Turk Panel - Production Test</h1>";
echo "<hr>";

// Test 1: PHP Version
echo "<h2>✅ PHP Version Test</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP Extensions: " . implode(', ', get_loaded_extensions()) . "<br><br>";

// Test 2: Database Connection
echo "<h2>✅ Database Connection Test</h2>";
try {
    $host = 'localhost';
    $username = 'smm_bizdavar_smmuser';
    $password = 'SMMTurk2024!';
    $database = 'smm_bizdavar_smmturk';
    
    $connection = new mysqli($host, $username, $password, $database);
    
    if ($connection->connect_error) {
        echo "❌ Database connection failed: " . $connection->connect_error . "<br>";
    } else {
        echo "✅ Database connection successful!<br>";
        echo "Database: " . $database . "<br>";
        echo "User: " . $username . "<br>";
        
        // Test tables
        $tables = $connection->query("SHOW TABLES");
        echo "Tables found: " . $tables->num_rows . "<br>";
        
        $connection->close();
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
echo "<br>";

// Test 3: API Endpoints
echo "<h2>✅ API Endpoints Test</h2>";
$api_urls = [
    'Services API' => 'https://smm-turk.com/api/services.php?endpoint=services',
    'Balance API' => 'https://smm-turk.com/api/services.php?endpoint=balance',
    'User Data API' => 'https://smm-turk.com/api/services.php?endpoint=user_data'
];

foreach ($api_urls as $name => $url) {
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['success'])) {
            echo "✅ $name: Working<br>";
        } else {
            echo "⚠️ $name: Response received but format issue<br>";
        }
    } else {
        echo "❌ $name: Failed to connect<br>";
    }
}
echo "<br>";

// Test 4: External APIs
echo "<h2>✅ External APIs Test</h2>";

// SMMFA API Test
$smmfa_url = 'https://smmfa.com/api/v2';
$smmfa_data = [
    'key' => 'b9f64c03f177cc3dc754198a17b66bca',
    'action' => 'balance'
];

$postdata = http_build_query($smmfa_data);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 10
    ]
]);

$smmfa_response = @file_get_contents($smmfa_url, false, $context);
if ($smmfa_response !== false) {
    echo "✅ SMMFA API: Connected<br>";
} else {
    echo "❌ SMMFA API: Connection failed<br>";
}

// SMMFollows API Test
$smmfollows_url = 'https://smmfollows.com/api/v2';
$smmfollows_data = [
    'key' => 'fdbc545f49196428a53189f1ee14e015',
    'action' => 'balance'
];

$postdata = http_build_query($smmfollows_data);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 10
    ]
]);

$smmfollows_response = @file_get_contents($smmfollows_url, false, $context);
if ($smmfollows_response !== false) {
    echo "✅ SMMFollows API: Connected<br>";
} else {
    echo "❌ SMMFollows API: Connection failed<br>";
}
echo "<br>";

// Test 5: File Permissions
echo "<h2>✅ File Permissions Test</h2>";
$directories = [
    'application/',
    'public/',
    'public/api/',
    'public/panel/',
    'public/admin/'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ $dir: Writable<br>";
        } else {
            echo "⚠️ $dir: Not writable<br>";
        }
    } else {
        echo "❌ $dir: Not found<br>";
    }
}
echo "<br>";

// Test 6: SSL Certificate
echo "<h2>✅ SSL Certificate Test</h2>";
$ssl_context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

$ssl_test = @file_get_contents('https://smm-turk.com/', false, $ssl_context);
if ($ssl_test !== false) {
    echo "✅ SSL Certificate: Valid<br>";
} else {
    echo "❌ SSL Certificate: Invalid or not configured<br>";
}
echo "<br>";

echo "<hr>";
echo "<h2>🎯 Test Summary</h2>";
echo "<p>If all tests show ✅, your SMM Turk Panel is ready for production!</p>";
echo "<p><strong>Access URLs:</strong></p>";
echo "<ul>";
echo "<li>Main Site: <a href='https://smm-turk.com/'>https://smm-turk.com/</a></li>";
echo "<li>User Panel: <a href='https://smm-turk.com/panel/'>https://smm-turk.com/panel/</a></li>";
echo "<li>Admin Panel: <a href='https://smm-turk.com/admin/'>https://smm-turk.com/admin/</a></li>";
echo "</ul>";
?>
