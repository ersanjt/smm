<?php
// SMM Turk Panel - Database Configuration Template
// Replace the values with the working configuration from the test

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

// Production MySQL (cPanel) - UPDATE THESE VALUES
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'REPLACE_WITH_WORKING_USERNAME',    // Update this
    'password' => 'REPLACE_WITH_WORKING_PASSWORD',    // Update this
    'database' => 'REPLACE_WITH_WORKING_DATABASE',    // Update this
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
?>
