<?php
// SMM Turk Panel - Database Connection Test
// Test different database credentials

echo "<h1>🔧 Database Connection Test</h1>";
echo "<hr>";

// Test different possible credentials
$test_configs = [
    'Config 1' => [
        'hostname' => 'localhost',
        'username' => 'smm_bizdavar_smmuser',
        'password' => 'SMMTurk2024!',
        'database' => 'smm_bizdavar_smmturk'
    ],
    'Config 2' => [
        'hostname' => 'localhost',
        'username' => 'bizdavar_smmuser',
        'password' => 'SMMTurk2024!',
        'database' => 'bizdavar_smmturk'
    ],
    'Config 3' => [
        'hostname' => 'localhost',
        'username' => 'smm_bizdavar_smmuser',
        'password' => '',
        'database' => 'smm_bizdavar_smmturk'
    ],
    'Config 4' => [
        'hostname' => 'localhost',
        'username' => 'smm_bizdavar_smmuser',
        'password' => 'password',
        'database' => 'smm_bizdavar_smmturk'
    ]
];

foreach ($test_configs as $config_name => $config) {
    echo "<h3>🧪 Testing: $config_name</h3>";
    echo "Host: " . $config['hostname'] . "<br>";
    echo "User: " . $config['username'] . "<br>";
    echo "Database: " . $config['database'] . "<br>";
    echo "Password: " . (empty($config['password']) ? '(empty)' : '***') . "<br>";
    
    try {
        $connection = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        
        if ($connection->connect_error) {
            echo "❌ Connection failed: " . $connection->connect_error . "<br>";
        } else {
            echo "✅ Connection successful!<br>";
            
            // Test query
            $result = $connection->query("SHOW TABLES");
            if ($result) {
                echo "✅ Tables found: " . $result->num_rows . "<br>";
            }
            
            $connection->close();
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    echo "<br>";
}

echo "<hr>";
echo "<h2>🔍 Database Information</h2>";

// Get current user
$connection = new mysqli('localhost', 'root', '');
if (!$connection->connect_error) {
    $result = $connection->query("SELECT USER() as current_user");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Current MySQL User: " . $row['current_user'] . "<br>";
    }
    
    // List databases
    $result = $connection->query("SHOW DATABASES");
    if ($result) {
        echo "<h3>Available Databases:</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['Database'] . "<br>";
        }
    }
    
    // List users
    $result = $connection->query("SELECT User, Host FROM mysql.user WHERE User LIKE '%smm%' OR User LIKE '%bizdavar%'");
    if ($result) {
        echo "<h3>Available Users:</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "- User: " . $row['User'] . ", Host: " . $row['Host'] . "<br>";
        }
    }
    
    $connection->close();
}

echo "<hr>";
echo "<h2>💡 Recommendations</h2>";
echo "<p>1. Check the correct database name in cPanel</p>";
echo "<p>2. Check the correct username in cPanel</p>";
echo "<p>3. Check the correct password in cPanel</p>";
echo "<p>4. Make sure the user has access to the database</p>";
?>
