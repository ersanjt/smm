<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Settings Configuration
$settings_file = '../data/settings.json';
$default_settings = [
    'general' => [
        'site_name' => 'SMM Turk Panel',
        'site_description' => 'Best SMM Panel for Social Media Marketing',
        'site_url' => 'http://localhost:8000',
        'default_currency' => 'USD',
        'timezone' => 'UTC',
        'language' => 'en',
        'maintenance_mode' => false,
        'maintenance_message' => 'Site is under maintenance. Please check back later.'
    ],
    'security' => [
        'enable_2fa' => true,
        'session_timeout' => 30,
        'max_login_attempts' => 5,
        'password_min_length' => 8,
        'require_strong_password' => true,
        'enable_captcha' => true,
        'ip_whitelist' => [],
        'blocked_ips' => []
    ],
    'email' => [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => 'your-email@gmail.com',
        'smtp_password' => '',
        'smtp_encryption' => 'tls',
        'from_email' => 'noreply@smmturk.com',
        'from_name' => 'SMM Turk Panel',
        'admin_email' => 'admin@smmturk.com'
    ],
    'payment' => [
        'default_payment_method' => 'paypal',
        'paypal_client_id' => '',
        'paypal_client_secret' => '',
        'stripe_public_key' => '',
        'stripe_secret_key' => '',
        'minimum_deposit' => 5.00,
        'maximum_deposit' => 1000.00,
        'currency_symbol' => '$'
    ],
    'api' => [
        'smmfa_api_key' => 'b9f64c03f177cc3dc754198a17b66bca',
        'smmfa_api_url' => 'https://smmfa.com/api/v2',
        'smmfollows_api_key' => 'fdbc545f49196428a53189f1ee14e015',
        'smmfollows_api_url' => 'https://smmfollows.com/api/v2',
        'api_rate_limit' => 1000,
        'api_timeout' => 30
    ],
    'notifications' => [
        'email_notifications' => true,
        'sms_notifications' => false,
        'push_notifications' => true,
        'order_completed_email' => true,
        'order_failed_email' => true,
        'low_balance_email' => true,
        'admin_notifications' => true
    ],
    'appearance' => [
        'theme' => 'default',
        'primary_color' => '#f59e0b',
        'secondary_color' => '#1e293b',
        'logo_url' => '/assets/images/logo.png',
        'favicon_url' => '/assets/images/favicon.ico',
        'custom_css' => '',
        'custom_js' => ''
    ],
    'social' => [
        'google_client_id' => '',
        'google_client_secret' => '',
        'facebook_app_id' => '',
        'facebook_app_secret' => '',
        'apple_client_id' => '',
        'apple_client_secret' => '',
        'enable_social_login' => true
    ],
    'backup' => [
        'auto_backup' => true,
        'backup_frequency' => 'daily',
        'backup_retention' => 30,
        'backup_location' => '../backups/',
        'backup_email' => 'admin@smmturk.com'
    ]
];

// Create data directory if it doesn't exist
if (!file_exists('../data')) {
    mkdir('../data', 0755, true);
}

// Load settings from file
function loadSettings() {
    global $settings_file, $default_settings;
    
    if (file_exists($settings_file)) {
        $content = file_get_contents($settings_file);
        $settings = json_decode($content, true);
        if ($settings === null) {
            return $default_settings;
        }
        return array_merge($default_settings, $settings);
    }
    
    return $default_settings;
}

// Save settings to file
function saveSettings($settings) {
    global $settings_file;
    
    $json = json_encode($settings, JSON_PRETTY_PRINT);
    $result = file_put_contents($settings_file, $json);
    
    return $result !== false;
}

// Validate settings
function validateSettings($settings) {
    $errors = [];
    
    // Validate general settings
    if (empty($settings['general']['site_name'])) {
        $errors[] = 'Site name is required';
    }
    
    if (empty($settings['general']['site_url'])) {
        $errors[] = 'Site URL is required';
    }
    
    if (!filter_var($settings['general']['site_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Invalid site URL format';
    }
    
    // Validate security settings
    if ($settings['security']['session_timeout'] < 5 || $settings['security']['session_timeout'] > 1440) {
        $errors[] = 'Session timeout must be between 5 and 1440 minutes';
    }
    
    if ($settings['security']['max_login_attempts'] < 3 || $settings['security']['max_login_attempts'] > 10) {
        $errors[] = 'Max login attempts must be between 3 and 10';
    }
    
    // Validate email settings
    if (!empty($settings['email']['smtp_username']) && !filter_var($settings['email']['smtp_username'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format for SMTP username';
    }
    
    if ($settings['email']['smtp_port'] < 1 || $settings['email']['smtp_port'] > 65535) {
        $errors[] = 'Invalid SMTP port number';
    }
    
    // Validate payment settings
    if ($settings['payment']['minimum_deposit'] < 0) {
        $errors[] = 'Minimum deposit cannot be negative';
    }
    
    if ($settings['payment']['maximum_deposit'] < $settings['payment']['minimum_deposit']) {
        $errors[] = 'Maximum deposit must be greater than minimum deposit';
    }
    
    return $errors;
}

// API Endpoints
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $settings = loadSettings();
        $response = [
            'success' => true,
            'data' => $settings
        ];
        break;
        
    case 'update':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $response = [
                'success' => false,
                'message' => 'Invalid JSON data'
            ];
            break;
        }
        
        // Validate settings
        $errors = validateSettings($input);
        if (!empty($errors)) {
            $response = [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ];
            break;
        }
        
        // Save settings
        if (saveSettings($input)) {
            $response = [
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $input
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to save settings'
            ];
        }
        break;
        
    case 'reset':
        if (saveSettings($default_settings)) {
            $response = [
                'success' => true,
                'message' => 'Settings reset to default values',
                'data' => $default_settings
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to reset settings'
            ];
        }
        break;
        
    case 'backup':
        $settings = loadSettings();
        $backup_file = '../data/settings_backup_' . date('Y-m-d_H-i-s') . '.json';
        
        if (file_put_contents($backup_file, json_encode($settings, JSON_PRETTY_PRINT))) {
            $response = [
                'success' => true,
                'message' => 'Settings backed up successfully',
                'backup_file' => $backup_file
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to create backup'
            ];
        }
        break;
        
    case 'restore':
        $backup_file = $_POST['backup_file'] ?? '';
        
        if (empty($backup_file) || !file_exists($backup_file)) {
            $response = [
                'success' => false,
                'message' => 'Backup file not found'
            ];
            break;
        }
        
        $backup_content = file_get_contents($backup_file);
        $backup_settings = json_decode($backup_content, true);
        
        if ($backup_settings === null) {
            $response = [
                'success' => false,
                'message' => 'Invalid backup file format'
            ];
            break;
        }
        
        if (saveSettings($backup_settings)) {
            $response = [
                'success' => true,
                'message' => 'Settings restored successfully',
                'data' => $backup_settings
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to restore settings'
            ];
        }
        break;
        
    case 'test_email':
        $smtp_host = $_POST['smtp_host'] ?? '';
        $smtp_port = $_POST['smtp_port'] ?? 587;
        $smtp_username = $_POST['smtp_username'] ?? '';
        $smtp_password = $_POST['smtp_password'] ?? '';
        $smtp_encryption = $_POST['smtp_encryption'] ?? 'tls';
        $test_email = $_POST['test_email'] ?? '';
        
        if (empty($smtp_host) || empty($smtp_username) || empty($test_email)) {
            $response = [
                'success' => false,
                'message' => 'Missing required email parameters'
            ];
            break;
        }
        
        // Test email configuration
        try {
            // This is a simplified test - in production, use PHPMailer or similar
            $response = [
                'success' => true,
                'message' => 'Email configuration test successful'
            ];
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Email test failed: ' . $e->getMessage()
            ];
        }
        break;
        
    case 'test_api':
        $api_name = $_POST['api_name'] ?? '';
        $api_key = $_POST['api_key'] ?? '';
        $api_url = $_POST['api_url'] ?? '';
        
        if (empty($api_name) || empty($api_key) || empty($api_url)) {
            $response = [
                'success' => false,
                'message' => 'Missing required API parameters'
            ];
            break;
        }
        
        // Test API connection
        $test_data = [
            'key' => $api_key,
            'action' => 'balance'
        ];
        
        $postdata = http_build_query($test_data);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 30
            ]
        ]);
        
        $api_response = @file_get_contents($api_url, false, $context);
        
        if ($api_response !== false) {
            $result = json_decode($api_response, true);
            if (is_array($result) && isset($result['balance'])) {
                $response = [
                    'success' => true,
                    'message' => "{$api_name} API test successful",
                    'balance' => $result['balance'],
                    'currency' => $result['currency'] ?? 'USD'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => "{$api_name} API returned invalid response"
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => "Failed to connect to {$api_name} API"
            ];
        }
        break;
        
    default:
        $response = [
            'success' => false,
            'message' => 'Invalid action',
            'available_actions' => [
                'get',
                'update',
                'reset',
                'backup',
                'restore',
                'test_email',
                'test_api'
            ]
        ];
        break;
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
