<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| تنظیمات امنیتی پیشرفته
| -------------------------------------------------------------------
| این فایل شامل تمام تنظیمات امنیتی برای SMM Panel است
*/

// تنظیمات امنیتی
$config['security'] = array(
    // تنظیمات Session
    'session' => array(
        'lifetime' => 7200, // 2 ساعت
        'regenerate_id' => true,
        'use_strict_mode' => true,
        'use_cookies' => true,
        'cookie_httponly' => true,
        'cookie_secure' => true, // فقط HTTPS
        'cookie_samesite' => 'Strict'
    ),
    
    // تنظیمات CSRF Protection
    'csrf' => array(
        'token_name' => 'csrf_token',
        'token_length' => 32,
        'regenerate_on_login' => true,
        'expire_time' => 3600 // 1 ساعت
    ),
    
    // تنظیمات Password
    'password' => array(
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'hash_algorithm' => 'bcrypt',
        'cost' => 12
    ),
    
    // تنظیمات Login Protection
    'login_protection' => array(
        'max_attempts' => 5,
        'lockout_duration' => 900, // 15 دقیقه
        'ip_whitelist' => array(),
        'ip_blacklist' => array(),
        'captcha_after_attempts' => 3
    ),
    
    // تنظیمات File Upload
    'file_upload' => array(
        'allowed_types' => array('jpg', 'jpeg', 'png', 'gif', 'pdf'),
        'max_size' => 5242880, // 5MB
        'upload_path' => './uploads/',
        'encrypt_filenames' => true,
        'scan_for_viruses' => true
    ),
    
    // تنظیمات SQL Injection Protection
    'sql_protection' => array(
        'use_prepared_statements' => true,
        'escape_strings' => true,
        'validate_input' => true,
        'log_suspicious_queries' => true
    ),
    
    // تنظیمات XSS Protection
    'xss_protection' => array(
        'filter_input' => true,
        'escape_output' => true,
        'use_content_security_policy' => true,
        'sanitize_user_input' => true
    ),
    
    // تنظیمات Rate Limiting
    'rate_limiting' => array(
        'enabled' => true,
        'max_requests_per_minute' => 60,
        'max_requests_per_hour' => 1000,
        'block_duration' => 3600 // 1 ساعت
    ),
    
    // تنظیمات Encryption
    'encryption' => array(
        'key' => 'your_32_character_encryption_key_here',
        'cipher' => 'AES-256-CBC',
        'iv_length' => 16
    ),
    
    // تنظیمات Logging
    'logging' => array(
        'log_failed_logins' => true,
        'log_suspicious_activity' => true,
        'log_file_access' => true,
        'log_database_queries' => false,
        'log_level' => 'INFO'
    )
);

// تنظیمات Content Security Policy
$config['csp'] = array(
    'default-src' => "'self'",
    'script-src' => "'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
    'style-src' => "'self' 'unsafe-inline' https://fonts.googleapis.com",
    'font-src' => "'self' https://fonts.gstatic.com",
    'img-src' => "'self' data: https:",
    'connect-src' => "'self'",
    'frame-ancestors' => "'none'",
    'base-uri' => "'self'",
    'form-action' => "'self'"
);

// تنظیمات Headers امنیتی
$config['security_headers'] = array(
    'X-Content-Type-Options' => 'nosniff',
    'X-Frame-Options' => 'DENY',
    'X-XSS-Protection' => '1; mode=block',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()'
);
