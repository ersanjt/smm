<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| تنظیمات اتصال به دیتابیس برای cPanel
| -------------------------------------------------------------------
| این فایل برای تنظیمات cPanel استفاده می‌شود
| نام دیتابیس و کاربر معمولاً پیشوند cPanel username دارند
*/

$active_group  = 'default';
$query_builder = TRUE;

// Database configuration for cPanel
$db['default'] = array(
    'dsn'      => '',
    'hostname' => '92.205.182.143', // IP سرور شما
    'username' => 'your_cpanel_username_dbuser', // نام کاربری دیتابیس
    'password' => 'your_database_password', // رمز دیتابیس
    'database' => 'your_cpanel_username_dbname', // نام دیتابیس
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
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
