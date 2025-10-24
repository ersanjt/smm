<?php
// SMM Turk Panel - Database Connection Tester
// Test all possible database configurations

echo "<h1>🧪 Database Connection Tester</h1>";
echo "<hr>";

// All possible configurations to test
$test_configs = [
    'Config 1' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config 2' => [
        'user' => 'bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'bizdavar_smmturk'
    ],
    'Config 3' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => '',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config 4' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'password',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config 5' => [
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'admin123',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config 6' => [
        'user' => 'bizdavar_smmuser',
        'pass' => '',
        'db' => 'bizdavar_smmturk'
    ],
    'Config 7' => [
        'user' => 'smmturk_user',
        'pass' => 'SMMTurk2024!',
        'db' => 'smmturk_main'
    ],
    'Config 8' => [
        'user' => 'smm_user',
        'pass' => 'SMMTurk2024!',
        'db' => 'smm_main'
    ]
];

$working_configs = [];

foreach ($test_configs as $name => $config) {
    echo "<h3>🧪 Testing: $name</h3>";
    echo "<strong>User:</strong> " . $config['user'] . "<br>";
    echo "<strong>Database:</strong> " . $config['db'] . "<br>";
    echo "<strong>Password:</strong> " . (empty($config['pass']) ? '(empty)' : '***') . "<br>";
    
    $conn = new mysqli('localhost', $config['user'], $config['pass'], $config['db']);
    
    if ($conn->connect_error) {
        echo "❌ <strong>Failed:</strong> " . $conn->connect_error . "<br>";
    } else {
        echo "✅ <strong>SUCCESS!</strong> This configuration works!<br>";
        
        // Test tables
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            echo "📊 <strong>Tables found:</strong> " . $result->num_rows . "<br>";
            
            // Show table names
            echo "📋 <strong>Table names:</strong> ";
            $table_names = [];
            while ($row = $result->fetch_array()) {
                $table_names[] = $row[0];
            }
            echo implode(', ', $table_names) . "<br>";
        }
        
        $working_configs[] = [
            'name' => $name,
            'config' => $config
        ];
        
        $conn->close();
    }
    echo "<br>";
}

echo "<hr>";

if (empty($working_configs)) {
    echo "<h2>❌ No Working Configurations Found</h2>";
    echo "<p>Please check your cPanel database settings:</p>";
    echo "<ul>";
    echo "<li>Database name</li>";
    echo "<li>User name</li>";
    echo "<li>User password</li>";
    echo "<li>User privileges</li>";
    echo "</ul>";
} else {
    echo "<h2>✅ Working Configurations Found!</h2>";
    foreach ($working_configs as $working) {
        echo "<h3>🎯 " . $working['name'] . " - WORKING!</h3>";
        echo "<p><strong>Use this configuration:</strong></p>";
        echo "<pre>";
        echo "'hostname' => 'localhost',<br>";
        echo "'username' => '" . $working['config']['user'] . "',<br>";
        echo "'password' => '" . $working['config']['pass'] . "',<br>";
        echo "'database' => '" . $working['config']['db'] . "',<br>";
        echo "</pre>";
    }
}

echo "<hr>";
echo "<h2>📝 Next Steps:</h2>";
echo "<p>1. Copy the working configuration above</p>";
echo "<p>2. Update <code>application/config/database.php</code></p>";
echo "<p>3. Test the main site</p>";
?>
