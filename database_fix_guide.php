<?php
// SMM Turk Panel - Database Fix Guide
// This file will help you fix the database connection issue

echo "<h1>🔧 Database Connection Fix Guide</h1>";
echo "<hr>";

echo "<h2>❌ Current Problem:</h2>";
echo "<p>Access denied for user 'smm_bizdavar_smmuser'@'localhost' to database 'smm_bizdavar_smmturk'</p>";

echo "<h2>✅ Solutions to Try:</h2>";

echo "<h3>1️⃣ Check Database Name in cPanel:</h3>";
echo "<p>Go to <strong>MySQL Databases</strong> in cPanel and check:</p>";
echo "<ul>";
echo "<li>Database name: <code>smm_bizdavar_smmturk</code></li>";
echo "<li>User name: <code>smm_bizdavar_smmuser</code></li>";
echo "<li>Make sure user has access to the database</li>";
echo "</ul>";

echo "<h3>2️⃣ Check User Password:</h3>";
echo "<p>Go to <strong>Current Users</strong> in cPanel:</p>";
echo "<ul>";
echo "<li>Find user: <code>smm_bizdavar_smmuser</code></li>";
echo "<li>Click <strong>Change Password</strong></li>";
echo "<li>Set password to: <code>SMMTurk2024!</code></li>";
echo "</ul>";

echo "<h3>3️⃣ Check Database Privileges:</h3>";
echo "<p>Make sure the user has ALL PRIVILEGES on the database:</p>";
echo "<ul>";
echo "<li>Go to <strong>MySQL Databases</strong></li>";
echo "<li>Find the user-database assignment</li>";
echo "<li>Make sure it shows <strong>ALL PRIVILEGES</strong></li>";
echo "</ul>";

echo "<h3>4️⃣ Alternative Database Names:</h3>";
echo "<p>Try these possible database names:</p>";
echo "<ul>";
echo "<li><code>bizdavar_smmturk</code></li>";
echo "<li><code>smmturk_main</code></li>";
echo "<li><code>smm_main</code></li>";
echo "</ul>";

echo "<h3>5️⃣ Alternative User Names:</h3>";
echo "<p>Try these possible user names:</p>";
echo "<ul>";
echo "<li><code>bizdavar_smmuser</code></li>";
echo "<li><code>smmturk_user</code></li>";
echo "<li><code>smm_user</code></li>";
echo "</ul>";

echo "<hr>";
echo "<h2>🧪 Test Different Configurations:</h2>";

// Test different possible configurations
$test_configs = [
    'Config A' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config B' => [
        'user' => 'bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'bizdavar_smmturk'
    ],
    'Config C' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => '',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config D' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'password',
        'db' => 'smm_bizdavar_smmturk'
    ]
];

foreach ($test_configs as $name => $config) {
    echo "<h4>Testing: $name</h4>";
    echo "User: " . $config['user'] . "<br>";
    echo "Database: " . $config['db'] . "<br>";
    echo "Password: " . (empty($config['pass']) ? '(empty)' : '***') . "<br>";
    
    $conn = new mysqli('localhost', $config['user'], $config['pass'], $config['db']);
    
    if ($conn->connect_error) {
        echo "❌ Failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ SUCCESS! This configuration works!<br>";
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            echo "Tables found: " . $result->num_rows . "<br>";
        }
        $conn->close();
    }
    echo "<br>";
}

echo "<hr>";
echo "<h2>💡 Next Steps:</h2>";
echo "<p>1. Find the working configuration above</p>";
echo "<p>2. Update <code>application/config/database.php</code> with the working credentials</p>";
echo "<p>3. Test the main site again</p>";
?>
