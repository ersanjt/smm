<?php
// SMM Turk Panel - Final Database Configuration
// This will be updated based on test results

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

// Database configuration for production
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'smm_bizdavar_smmuser',
    'password' => 'SMMTurk2024!',
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
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
?>
