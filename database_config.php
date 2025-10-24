<?php
// SMM Turk Panel - Database Configuration for cPanel
// Update this file with your actual database credentials

// Database configuration for smm-turk.com
$db_config = [
    'hostname' => 'localhost',
    'username' => 'smm_bizdavar_smmuser',
    'password' => 'SMMTurk2024!', // Change this to your actual password
    'database' => 'smm_bizdavar_smmturk',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];

// Test database connection
try {
    $connection = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
    
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
    echo "✅ Database connection successful!";
    echo "<br>Database: " . $db_config['database'];
    echo "<br>User: " . $db_config['username'];
    
    $connection->close();
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
