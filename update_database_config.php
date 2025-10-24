<?php
// SMM Turk Panel - Database Configuration Updater
// This file will be updated based on test results

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

// Database configuration - WILL BE UPDATED BASED ON TEST RESULTS
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'smm_bizdavar_smmuser', // Will be updated if needed
    'password' => 'SMMTurk2024!',         // Will be updated if needed
    'database' => 'smm_bizdavar_smmturk', // Will be updated if needed
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

// Test the connection
try {
    $connection = new mysqli(
        $db['default']['hostname'],
        $db['default']['username'],
        $db['default']['password'],
        $db['default']['database']
    );
    
    if ($connection->connect_error) {
        echo "❌ Database connection failed: " . $connection->connect_error;
        echo "<br><br>Please update the configuration based on test results.";
    } else {
        echo "✅ Database connection successful!";
        echo "<br>Host: " . $db['default']['hostname'];
        echo "<br>User: " . $db['default']['username'];
        echo "<br>Database: " . $db['default']['database'];
        
        // Test tables
        $result = $connection->query("SHOW TABLES");
        if ($result) {
            echo "<br>Tables found: " . $result->num_rows;
        }
        
        $connection->close();
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>
