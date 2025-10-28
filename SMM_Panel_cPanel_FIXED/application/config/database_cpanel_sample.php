<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| تنظیمات اتصال به دیتابیس (MySQL) - cPanel
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

// Production MySQL (cPanel) - WORKING CONFIG
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'YOUR_CPANEL_USERNAME_smmuser',  // تغییر دهید
    'password' => 'YOUR_MYSQL_PASSWORD',           // تغییر دهید
    'database' => 'YOUR_CPANEL_USERNAME_smmturk', // تغییر دهید
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,  // در production روی false بگذارید
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

/*
| -------------------------------------------------------------------
| مثال برای تنظیمات مختلف cPanel:
| -------------------------------------------------------------------
| 
| اگر نام کاربری cPanel شما "mycpanel" باشد:
| 'username' => 'mycpanel_smmuser',
| 'database' => 'mycpanel_smmturk',
| 
| اگر نام کاربری cPanel شما "bizdavar" باشد:
| 'username' => 'bizdavar_smmuser',
| 'database' => 'bizdavar_smmturk',
| 
| اگر هاست شما پیشوند ندارد:
| 'username' => 'smmuser',
| 'database' => 'smmturk',
*/
