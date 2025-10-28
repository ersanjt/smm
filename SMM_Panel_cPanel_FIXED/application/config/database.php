<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| تنظیمات اتصال به دیتابیس (MySQL)
| -------------------------------------------------------------------
| نکته مهم در cPanel:
| - معمولاً نام دیتابیس و نام کاربر، پیشوندِ نام کاربری cPanel را دارند.
|   مثلا اگر یوزر cPanel شما "mycpanel" باشد، نام دیتابیس و یوزر می‌تواند
|   چیزی مثل: mycpanel_bizdavar_smm باشد.
| - نام کاربری MySQL نمی‌تواند dash (-) داشته باشد.
| - اگر در هاست شما پیشوند ندارد، همان مقادیر پایین را استفاده کنید.
*/

$active_group  = 'default';
$query_builder = TRUE;

// Database configuration for different environments
if (getenv('DATABASE_URL')) {
    // Railway/Heroku PostgreSQL
    $url = parse_url(getenv('DATABASE_URL'));
    $db['default'] = array(
        'dsn'      => '',
        'hostname' => $url['host'],
        'username' => $url['user'],
        'password' => $url['pass'],
        'database' => substr($url['path'], 1),
        'dbdriver' => 'postgre',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt'  => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );
} elseif (getenv('PGHOST')) {
    // Railway PostgreSQL (alternative)
    $db['default'] = array(
        'dsn'      => '',
        'hostname' => getenv('PGHOST'),
        'username' => getenv('PGUSER'),
        'password' => getenv('PGPASSWORD'),
        'database' => getenv('PGDATABASE'),
        'dbdriver' => 'postgre',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt'  => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );
       } else {
           // Production MySQL (cPanel) - WORKING CONFIG
           $db['default'] = array(
               'dsn'      => '',
               'hostname' => 'localhost',
               'username' => 'bizdavar_smmuser',
               'password' => 'SMMTurk2024!',
               'database' => 'bizdavar_smmturk',
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
       }
