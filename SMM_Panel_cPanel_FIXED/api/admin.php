<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Admin API Configuration
$admin_config = [
    'version' => '1.0.0',
    'base_url' => 'http://localhost:8000/api/',
    'rate_limit' => 2000, // requests per hour for admin
    'timeout' => 30
];

// Mock Admin Data
$admin_stats = [
    'total_users' => 1234,
    'total_orders' => 5678,
    'total_revenue' => 45678.50,
    'success_rate' => 89,
    'active_users' => 856,
    'pending_orders' => 23,
    'completed_orders' => 5432,
    'cancelled_orders' => 223
];

$admin_users = [
    [
        'id' => 1,
        'username' => 'john_doe',
        'email' => 'john@example.com',
        'balance' => 125.50,
        'status' => 'active',
        'join_date' => '2025-01-15',
        'last_login' => '2025-01-24 10:30:00',
        'total_orders' => 45,
        'total_spent' => 234.75
    ],
    [
        'id' => 2,
        'username' => 'jane_smith',
        'email' => 'jane@example.com',
        'balance' => 89.25,
        'status' => 'active',
        'join_date' => '2025-01-20',
        'last_login' => '2025-01-24 09:15:00',
        'total_orders' => 32,
        'total_spent' => 156.80
    ],
    [
        'id' => 3,
        'username' => 'mike_wilson',
        'email' => 'mike@example.com',
        'balance' => 0.00,
        'status' => 'banned',
        'join_date' => '2025-01-10',
        'last_login' => '2025-01-22 14:20:00',
        'total_orders' => 12,
        'total_spent' => 67.50
    ]
];

$admin_orders = [
    [
        'id' => 1001,
        'user_id' => 1,
        'username' => 'john_doe',
        'service' => 'Instagram Followers',
        'link' => '@username',
        'quantity' => 1000,
        'charge' => 12.50,
        'status' => 'completed',
        'date' => '2025-01-24 10:30:00',
        'provider' => 'SMMFA',
        'start_count' => 1500,
        'delivered' => 1000,
        'remains' => 0
    ],
    [
        'id' => 1002,
        'user_id' => 2,
        'username' => 'jane_smith',
        'service' => 'Facebook Page Likes',
        'link' => 'facebook.com/page',
        'quantity' => 500,
        'charge' => 8.75,
        'status' => 'in-progress',
        'date' => '2025-01-24 08:15:00',
        'provider' => 'SMMFollows',
        'start_count' => 1200,
        'delivered' => 300,
        'remains' => 200
    ],
    [
        'id' => 1003,
        'user_id' => 1,
        'username' => 'john_doe',
        'service' => 'YouTube Views',
        'link' => 'youtube.com/watch?v=123',
        'quantity' => 2000,
        'charge' => 25.00,
        'status' => 'pending',
        'date' => '2025-01-24 06:45:00',
        'provider' => 'Local',
        'start_count' => 0,
        'delivered' => 0,
        'remains' => 2000
    ]
];

$admin_services = [
    [
        'id' => 1,
        'name' => 'Instagram Followers',
        'category' => 'Instagram',
        'description' => 'High quality Instagram followers',
        'cost_price' => 0.30,
        'sell_price' => 0.50,
        'markup' => 66.7,
        'status' => 'active',
        'total_orders' => 1234,
        'revenue' => 617.00,
        'provider' => 'SMMFA'
    ],
    [
        'id' => 2,
        'name' => 'Facebook Page Likes',
        'category' => 'Facebook',
        'description' => 'Real Facebook page likes',
        'cost_price' => 0.50,
        'sell_price' => 0.80,
        'markup' => 60.0,
        'status' => 'active',
        'total_orders' => 856,
        'revenue' => 684.80,
        'provider' => 'SMMFollows'
    ],
    [
        'id' => 3,
        'name' => 'YouTube Views',
        'category' => 'YouTube',
        'description' => 'High retention YouTube views',
        'cost_price' => 1.20,
        'sell_price' => 2.00,
        'markup' => 66.7,
        'status' => 'active',
        'total_orders' => 432,
        'revenue' => 864.00,
        'provider' => 'Local'
    ]
];

$admin_tickets = [
    [
        'id' => 'T001',
        'user_id' => 1,
        'username' => 'john_doe',
        'subject' => 'Order not delivered',
        'priority' => 'high',
        'status' => 'open',
        'created' => '2025-01-24',
        'last_reply' => '2025-01-24 11:30:00',
        'replies' => 3
    ],
    [
        'id' => 'T002',
        'user_id' => 2,
        'username' => 'jane_smith',
        'subject' => 'Payment issue',
        'priority' => 'medium',
        'status' => 'pending',
        'created' => '2025-01-23',
        'last_reply' => '2025-01-23 16:45:00',
        'replies' => 1
    ],
    [
        'id' => 'T003',
        'user_id' => 3,
        'username' => 'mike_wilson',
        'subject' => 'Account suspension',
        'priority' => 'low',
        'status' => 'resolved',
        'created' => '2025-01-22',
        'last_reply' => '2025-01-23 09:20:00',
        'replies' => 5
    ]
];

// API Endpoints
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    case 'stats':
        $response = [
            'success' => true,
            'data' => $admin_stats
        ];
        break;
        
    case 'users':
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'all';
        
        $filtered_users = $admin_users;
        
        // Apply search filter
        if (!empty($search)) {
            $filtered_users = array_filter($filtered_users, function($user) use ($search) {
                return stripos($user['username'], $search) !== false || 
                       stripos($user['email'], $search) !== false;
            });
        }
        
        // Apply status filter
        if ($status !== 'all') {
            $filtered_users = array_filter($filtered_users, function($user) use ($status) {
                return $user['status'] === $status;
            });
        }
        
        $total = count($filtered_users);
        $offset = ($page - 1) * $limit;
        $paginated_users = array_slice($filtered_users, $offset, $limit);
        
        $response = [
            'success' => true,
            'data' => $paginated_users,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ];
        break;
        
    case 'orders':
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);
        $status = $_GET['status'] ?? 'all';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        
        $filtered_orders = $admin_orders;
        
        // Apply status filter
        if ($status !== 'all') {
            $filtered_orders = array_filter($filtered_orders, function($order) use ($status) {
                return $order['status'] === $status;
            });
        }
        
        // Apply date filter
        if (!empty($date_from) && !empty($date_to)) {
            $filtered_orders = array_filter($filtered_orders, function($order) use ($date_from, $date_to) {
                $order_date = date('Y-m-d', strtotime($order['date']));
                return $order_date >= $date_from && $order_date <= $date_to;
            });
        }
        
        $total = count($filtered_orders);
        $offset = ($page - 1) * $limit;
        $paginated_orders = array_slice($filtered_orders, $offset, $limit);
        
        $response = [
            'success' => true,
            'data' => $paginated_orders,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ];
        break;
        
    case 'services':
        $response = [
            'success' => true,
            'data' => $admin_services
        ];
        break;
        
    case 'tickets':
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);
        $status = $_GET['status'] ?? 'all';
        $priority = $_GET['priority'] ?? 'all';
        
        $filtered_tickets = $admin_tickets;
        
        // Apply status filter
        if ($status !== 'all') {
            $filtered_tickets = array_filter($filtered_tickets, function($ticket) use ($status) {
                return $ticket['status'] === $status;
            });
        }
        
        // Apply priority filter
        if ($priority !== 'all') {
            $filtered_tickets = array_filter($filtered_tickets, function($ticket) use ($priority) {
                return $ticket['priority'] === $priority;
            });
        }
        
        $total = count($filtered_tickets);
        $offset = ($page - 1) * $limit;
        $paginated_tickets = array_slice($filtered_tickets, $offset, $limit);
        
        $response = [
            'success' => true,
            'data' => $paginated_tickets,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ];
        break;
        
    case 'api_status':
        // Test external APIs
        $smmfa_status = testSMMFAAPI();
        $smmfollows_status = testSMMFollowsAPI();
        
        $response = [
            'success' => true,
            'data' => [
                'smmfa' => $smmfa_status,
                'smmfollows' => $smmfollows_status,
                'local' => [
                    'status' => 'online',
                    'response_time' => '5ms',
                    'last_check' => date('Y-m-d H:i:s')
                ]
            ]
        ];
        break;
        
    case 'update_user':
        $user_id = $_POST['user_id'] ?? null;
        $action = $_POST['action'] ?? '';
        
        if (!$user_id || !$action) {
            $response = [
                'success' => false,
                'message' => 'Missing required parameters'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => "User {$user_id} {$action} successfully"
            ];
        }
        break;
        
    case 'update_order':
        $order_id = $_POST['order_id'] ?? null;
        $action = $_POST['action'] ?? '';
        
        if (!$order_id || !$action) {
            $response = [
                'success' => false,
                'message' => 'Missing required parameters'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => "Order {$order_id} {$action} successfully"
            ];
        }
        break;
        
    case 'update_service':
        $service_id = $_POST['service_id'] ?? null;
        $action = $_POST['action'] ?? '';
        
        if (!$service_id || !$action) {
            $response = [
                'success' => false,
                'message' => 'Missing required parameters'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => "Service {$service_id} {$action} successfully"
            ];
        }
        break;
        
    case 'update_settings':
        $settings = $_POST['settings'] ?? [];
        
        if (empty($settings)) {
            $response = [
                'success' => false,
                'message' => 'No settings provided'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $settings
            ];
        }
        break;
        
    default:
        $response = [
            'success' => false,
            'error' => 'Invalid endpoint',
            'available_endpoints' => [
                'stats',
                'users',
                'orders',
                'services',
                'tickets',
                'api_status',
                'update_user',
                'update_order',
                'update_service',
                'update_settings'
            ]
        ];
        break;
}

// Helper Functions
function testSMMFAAPI() {
    // Simulate API test
    return [
        'status' => 'online',
        'response_time' => '120ms',
        'balance' => '$0.00',
        'last_check' => date('Y-m-d H:i:s')
    ];
}

function testSMMFollowsAPI() {
    // Test SMMFollows API with real API key
    $api_key = 'fdbc545f49196428a53189f1ee14e015';
    $base_url = 'https://smmfollows.com/api/v2';
    
    // Test balance endpoint
    $data = [
        'key' => $api_key,
        'action' => 'balance'
    ];
    
    $postdata = http_build_query($data);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 30
        ]
    ]);
    
    $response = @file_get_contents($base_url, false, $context);
    
    if ($response !== false) {
        $result = json_decode($response, true);
        if (is_array($result) && isset($result['balance'])) {
            return [
                'status' => 'online',
                'response_time' => '120ms',
                'balance' => '$' . $result['balance'] . ' ' . ($result['currency'] ?? 'USD'),
                'last_check' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    // Fallback if API fails
    return [
        'status' => 'offline',
        'response_time' => 'N/A',
        'balance' => 'N/A',
        'last_check' => 'Never'
    ];
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
