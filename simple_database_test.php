<?php
// Simple Database Connection Test
// Upload this file to public_html/ and run it

echo "<h1>🔧 Simple Database Test</h1>";
echo "<hr>";

// Test database connection with different credentials
$test_configs = [
    'Config 1' => [
        'host' => 'localhost',
        'user' => 'smm_bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'smm_bizdavar_smmturk'
    ],
    'Config 2' => [
        'host' => 'localhost',
        'user' => 'bizdavar_smmuser',
        'pass' => 'SMMTurk2024!',
        'db' => 'bizdavar_smmturk'
    ],
    'Config 3' => [
        'host' => 'localhost',
        'user' => 'smm_bizdavar_smmuser',
        'pass' => '',
        'db' => 'smm_bizdavar_smmturk'
    ]
];

foreach ($test_configs as $name => $config) {
    echo "<h3>Testing: $name</h3>";
    echo "User: " . $config['user'] . "<br>";
    echo "Database: " . $config['db'] . "<br>";
    
    $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
    
    if ($conn->connect_error) {
        echo "❌ Failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Success!<br>";
        $result = $conn->query("SHOW TABLES");
        echo "Tables: " . $result->num_rows . "<br>";
        $conn->close();
    }
    echo "<br>";
}

echo "<hr>";
echo "<h2>💡 Next Steps:</h2>";
echo "<p>1. Find the working configuration above</p>";
echo "<p>2. Update application/config/database.php with correct credentials</p>";
echo "<p>3. Test the main site</p>";
?>
