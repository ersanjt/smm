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

$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',                 // معمولا در هاست اشتراکی localhost است
    'username' => 'root',              // نام کاربر دیتابیس (در صورت وجود پیشوند، آن را اضافه کن)
    'password' => '',          // پسورد کاربر دیتابیس
    'database' => 'bizdavar_smm',              // نام دیتابیس (در صورت وجود پیشوند، آن را اضافه کن)
    'dbdriver' => 'mysqli',                    // در هاست‌های معمولی mysqli
    'dbprefix' => '',
    'pconnect' => FALSE,                       // اتصال پایدار توصیه نمی‌شود
    'db_debug' => (ENVIRONMENT !== 'production'), // در حالت production بهتر است FALSE باشد
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',                   // برای ایموجی و فارسی بهترین گزینه
    'dbcollat' => 'utf8mb4_unicode_ci',        // کولیشن مناسب فارسی/ایموجی
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
