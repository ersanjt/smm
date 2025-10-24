<?php
// SMM Turk Panel - Fixed Database Configuration
// Use this after finding the correct credentials

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

// Production MySQL (cPanel) - FIXED VERSION
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'smm_bizdavar_smmuser', // Update this if needed
    'password' => 'SMMTurk2024!',         // Update this if needed
    'database' => 'smm_bizdavar_smmturk', // Update this if needed
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE, // Set to FALSE for production
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

// Test connection
try {
    $connection = new mysqli(
        $db['default']['hostname'],
        $db['default']['username'],
        $db['default']['password'],
        $db['default']['database']
    );
    
    if ($connection->connect_error) {
        echo "❌ Database connection failed: " . $connection->connect_error;
    } else {
        echo "✅ Database connection successful!";
        $connection->close();
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>
