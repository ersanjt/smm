<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// API Configuration
$api_config = [
    'version' => '1.0.0',
    'base_url' => 'http://localhost:8000/api/',
    'rate_limit' => 1000, // requests per hour
    'timeout' => 30
];

// External API Configuration (SMMFA)
$external_api_config = [
    'base_url' => 'https://smmfa.com/api/v2',
    'api_key' => 'b9f64c03f177cc3dc754198a17b66bca',
    'timeout' => 30
];

// External API Configuration (SMMFollows)
$smmfollows_api_config = [
    'base_url' => 'https://smmfollows.com/api/v2',
    'api_key' => 'fdbc545f49196428a53189f1ee14e015',
    'timeout' => 30
];

// No local services - only external APIs
$services_data = [];

// User data (mock)
$user_data = [
    'id' => 1,
    'username' => 'user',
    'email' => 'user@smmturk.com',
    'balance' => 0.00,
    'total_orders' => 0,
    'total_spent' => 0.00,
    'registration_date' => '2024-01-01',
    'last_login' => date('Y-m-d H:i:s'),
    'status' => 'active'
];

// Stats data (mock)
$stats_data = [
    'total_orders' => 0,
    'completed_orders' => 0,
    'pending_orders' => 0,
    'cancelled_orders' => 0,
    'total_spent' => 0.00,
    'total_earned' => 0.00,
    'success_rate' => 100.0
];

// Orders data (mock)
$orders_data = [];

// Function to call SMMFA API
function callSMMFAAPI($action, $params = []) {
    global $external_api_config;
    
    $url = $external_api_config['base_url'];
    $data = array_merge([
        'key' => $external_api_config['api_key'],
        'action' => $action
    ], $params);
    
    // Use file_get_contents with POST context
    $postdata = http_build_query($data);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => $external_api_config['timeout']
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if (is_array($result) || is_object($result)) {
            return $result;
        }
    }
    
    return false;
}

// Function to call SMMFollows API
function callSMMFollowsAPI($action, $params = []) {
    global $smmfollows_api_config;
    
    $url = $smmfollows_api_config['base_url'];
    $data = array_merge([
        'key' => $smmfollows_api_config['api_key'],
        'action' => $action
    ], $params);
    
    // Use file_get_contents with POST context
    $postdata = http_build_query($data);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => $smmfollows_api_config['timeout']
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if (is_array($result) || is_object($result)) {
            return $result;
        }
    }
    
    return false;
}

// Fetch SMMFA Services
function fetchSMMFAServices() {
    $services = callSMMFAAPI('services');
    
    if ($services !== false) {
        return $services;
    }
    
    // Return mock data if API fails
    return [
        [
            'service' => 1,
            'name' => 'Instagram Followers - SMMFA',
            'type' => 'Default',
            'category' => 'Instagram',
            'rate' => '0.50',
            'min' => '100',
            'max' => '10000',
            'refill' => true,
            'cancel' => true
        ],
        [
            'service' => 2,
            'name' => 'Facebook Page Likes - SMMFA',
            'type' => 'Default',
            'category' => 'Facebook',
            'rate' => '0.80',
            'min' => '100',
            'max' => '5000',
            'refill' => true,
            'cancel' => true
        ]
    ];
}

// Fetch SMMFollows Services
function fetchSMMFollowsServices() {
    $services = callSMMFollowsAPI('services');
    
    if ($services !== false) {
        return $services;
    }
    
    // Return mock data if API fails
    return [
        [
            'service' => 1,
            'name' => 'Instagram Followers - SMMFollows',
            'type' => 'Default',
            'category' => 'Instagram',
            'rate' => '0.60',
            'min' => '100',
            'max' => '15000',
            'refill' => true,
            'cancel' => true
        ],
        [
            'service' => 2,
            'name' => 'YouTube Views - SMMFollows',
            'type' => 'Default',
            'category' => 'YouTube',
            'rate' => '1.20',
            'min' => '100',
            'max' => '100000',
            'refill' => true,
            'cancel' => true
        ]
    ];
}

// Convert SMMFA/SMMFollows services to our format
function convertSMMFAServices($services) {
    $converted = [];
    
    foreach ($services as $service) {
        $category = strtolower($service['category'] ?? 'other');
        if (!isset($converted[$category])) {
            $converted[$category] = [];
        }
        
        $converted[$category][] = [
            'id' => 'smmfa_' . $service['service'],
            'name' => $service['name'],
            'description' => 'High quality service from SMMFA',
            'price' => floatval($service['rate']),
            'min_quantity' => intval($service['min']),
            'max_quantity' => intval($service['max']),
            'start_time' => '0-1 hour',
            'speed' => '100-500 per day',
            'guarantee' => '30 days',
            'category' => $category,
            'icon' => 'fab fa-' . $category,
            'status' => 'active',
            'provider' => 'smmfa',
            'refill' => $service['refill'] ?? false,
            'cancel' => $service['cancel'] ?? false
        ];
    }
    
    return $converted;
}

// Get SMMFA Balance
function getSMMFABalance() {
    $balance = callSMMFAAPI('balance');
    
    if ($balance !== false) {
        return [
            'balance' => floatval($balance['balance'] ?? 0),
            'currency' => $balance['currency'] ?? 'USD'
        ];
    }
    
    return ['balance' => 0.00, 'currency' => 'USD'];
}

// Get SMMFollows Balance
function getSMMFollowsBalance() {
    $balance = callSMMFollowsAPI('balance');
    
    if ($balance !== false) {
        return [
            'balance' => floatval($balance['balance'] ?? 0),
            'currency' => $balance['currency'] ?? 'USD'
        ];
    }
    
    return ['balance' => 0.00, 'currency' => 'USD'];
}

// Add SMMFA Order
function addSMMFAOrder($service_id, $link, $quantity) {
    $order = callSMMFAAPI('add', [
        'service' => $service_id,
        'link' => $link,
        'quantity' => $quantity
    ]);
    
    if ($order !== false) {
        return [
            'success' => true,
            'order_id' => $order['order'] ?? null,
            'message' => 'Order created successfully'
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Failed to create order'
    ];
}

// Add SMMFollows Order
function addSMMFollowsOrder($service_id, $link, $quantity) {
    $order = callSMMFollowsAPI('add', [
        'service' => $service_id,
        'link' => $link,
        'quantity' => $quantity
    ]);
    
    if ($order !== false) {
        return [
            'success' => true,
            'order_id' => $order['order'] ?? null,
            'message' => 'Order created successfully'
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Failed to create order'
    ];
}

// Get endpoint from URL
$endpoint = $_GET['endpoint'] ?? 'services';

// Handle different endpoints
switch ($endpoint) {
    case 'services':
        $category = $_GET['category'] ?? 'all';
        $include_external = $_GET['external'] ?? 'true';
        
        // Get external services from SMMFA and SMMFollows
        $external_services = [];
        if ($include_external === 'true') {
            // SMMFA Services
            $smmfa_services = fetchSMMFAServices();
            if (!empty($smmfa_services)) {
                $smmfa_converted = convertSMMFAServices($smmfa_services);
                $external_services = array_merge_recursive($external_services, $smmfa_converted);
            }
            
            // SMMFollows Services
            $smmfollows_services = fetchSMMFollowsServices();
            if (!empty($smmfollows_services)) {
                $smmfollows_converted = convertSMMFAServices($smmfollows_services); // Same format
                $external_services = array_merge_recursive($external_services, $smmfollows_converted);
            }
        }
        
        if ($category === 'all') {
            $response = [
                'success' => true,
                'data' => $external_services,
                'total' => count($external_services, COUNT_RECURSIVE) - count($external_services),
                'providers' => ['smmfa', 'smmfollows']
            ];
        } else {
            $response = [
                'success' => true,
                'data' => $external_services[$category] ?? [],
                'total' => count($external_services[$category] ?? []),
                'providers' => ['smmfa', 'smmfollows']
            ];
        }
        break;
        
    case 'balance':
        $smmfa_balance = getSMMFABalance();
        $smmfollows_balance = getSMMFollowsBalance();
        $total_external_balance = $smmfa_balance['balance'] + $smmfollows_balance['balance'];
        
        $response = [
            'success' => true,
            'data' => [
                'smmfa_balance' => $smmfa_balance['balance'],
                'smmfa_currency' => $smmfa_balance['currency'],
                'smmfollows_balance' => $smmfollows_balance['balance'],
                'smmfollows_currency' => $smmfollows_balance['currency'],
                'total_external_balance' => $total_external_balance,
                'total_balance' => $total_external_balance
            ]
        ];
        break;
        
    case 'add_order':
        $service_id = $_POST['service_id'] ?? '';
        $link = $_POST['link'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 0);
        
        if (empty($service_id) || empty($link) || $quantity <= 0) {
            $response = [
                'success' => false,
                'message' => 'Missing required parameters'
            ];
        } else {
            // Determine provider from service ID
            if (strpos($service_id, 'smmfa_') === 0) {
                $actual_service_id = str_replace('smmfa_', '', $service_id);
                $result = addSMMFAOrder($actual_service_id, $link, $quantity);
            } elseif (strpos($service_id, 'smmfollows_') === 0) {
                $actual_service_id = str_replace('smmfollows_', '', $service_id);
                $result = addSMMFollowsOrder($actual_service_id, $link, $quantity);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid service ID'
                ];
                break;
            }
            
            $response = $result;
        }
        break;
        
    case 'user_data':
        $response = [
            'success' => true,
            'data' => $user_data
        ];
        break;
        
    case 'stats':
        $response = [
            'success' => true,
            'data' => $stats_data
        ];
        break;
        
    case 'orders':
        $response = [
            'success' => true,
            'data' => $orders_data
        ];
        break;
        
    default:
        $response = [
            'success' => false,
            'message' => 'Invalid endpoint'
        ];
        break;
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>