<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Tickets Configuration
$tickets_file = '../data/tickets.json';
$messages_file = '../data/ticket_messages.json';

// Create data directory if it doesn't exist
if (!file_exists('../data')) {
    mkdir('../data', 0755, true);
}

// Default tickets data
$default_tickets = [
    [
        'id' => 'T001',
        'user_id' => 1,
        'username' => 'john_doe',
        'subject' => 'Order not delivered',
        'priority' => 'high',
        'status' => 'open',
        'category' => 'order',
        'created_at' => '2025-01-24 10:30:00',
        'updated_at' => '2025-01-24 10:30:00',
        'assigned_to' => null,
        'last_reply' => '2025-01-24 10:30:00',
        'replies_count' => 0
    ],
    [
        'id' => 'T002',
        'user_id' => 2,
        'username' => 'jane_smith',
        'subject' => 'Payment issue',
        'priority' => 'medium',
        'status' => 'pending',
        'category' => 'payment',
        'created_at' => '2025-01-23 14:20:00',
        'updated_at' => '2025-01-23 16:45:00',
        'assigned_to' => 'admin',
        'last_reply' => '2025-01-23 16:45:00',
        'replies_count' => 2
    ],
    [
        'id' => 'T003',
        'user_id' => 3,
        'username' => 'mike_wilson',
        'subject' => 'Account suspension',
        'priority' => 'low',
        'status' => 'resolved',
        'category' => 'account',
        'created_at' => '2025-01-22 09:15:00',
        'updated_at' => '2025-01-22 17:30:00',
        'assigned_to' => 'admin',
        'last_reply' => '2025-01-22 17:30:00',
        'replies_count' => 4
    ],
    [
        'id' => 'T004',
        'user_id' => 4,
        'username' => 'sarah_jones',
        'subject' => 'Service quality issue',
        'priority' => 'high',
        'status' => 'open',
        'category' => 'service',
        'created_at' => '2025-01-24 08:45:00',
        'updated_at' => '2025-01-24 08:45:00',
        'assigned_to' => null,
        'last_reply' => '2025-01-24 08:45:00',
        'replies_count' => 0
    ],
    [
        'id' => 'T005',
        'user_id' => 5,
        'username' => 'alex_brown',
        'subject' => 'Refund request',
        'priority' => 'medium',
        'status' => 'pending',
        'category' => 'refund',
        'created_at' => '2025-01-23 11:30:00',
        'updated_at' => '2025-01-23 15:20:00',
        'assigned_to' => 'admin',
        'last_reply' => '2025-01-23 15:20:00',
        'replies_count' => 1
    ]
];

// Default messages data
$default_messages = [
    'T001' => [
        [
            'id' => 1,
            'ticket_id' => 'T001',
            'user_id' => 1,
            'username' => 'john_doe',
            'message' => 'Hi, I placed an order for Instagram followers 3 days ago but it hasn\'t been delivered yet. Order ID: #12345. Can you please check the status?',
            'type' => 'user',
            'created_at' => '2025-01-24 10:30:00',
            'attachments' => []
        ]
    ],
    'T002' => [
        [
            'id' => 1,
            'ticket_id' => 'T002',
            'user_id' => 2,
            'username' => 'jane_smith',
            'message' => 'I tried to make a payment but it keeps failing. I\'m getting an error message. Can you help?',
            'type' => 'user',
            'created_at' => '2025-01-23 14:20:00',
            'attachments' => []
        ],
        [
            'id' => 2,
            'ticket_id' => 'T002',
            'user_id' => 0,
            'username' => 'admin',
            'message' => 'Hello Jane, I can see the payment issue. Please try using a different payment method or contact your bank. If the problem persists, please provide more details about the error message.',
            'type' => 'admin',
            'created_at' => '2025-01-23 15:30:00',
            'attachments' => []
        ],
        [
            'id' => 3,
            'ticket_id' => 'T002',
            'user_id' => 2,
            'username' => 'jane_smith',
            'message' => 'Thanks for the quick response. I\'ll try a different payment method and let you know.',
            'type' => 'user',
            'created_at' => '2025-01-23 16:45:00',
            'attachments' => []
        ]
    ],
    'T003' => [
        [
            'id' => 1,
            'ticket_id' => 'T003',
            'user_id' => 3,
            'username' => 'mike_wilson',
            'message' => 'My account was suspended without any explanation. I haven\'t violated any terms. Please review my account.',
            'type' => 'user',
            'created_at' => '2025-01-22 09:15:00',
            'attachments' => []
        ],
        [
            'id' => 2,
            'ticket_id' => 'T003',
            'user_id' => 0,
            'username' => 'admin',
            'message' => 'Hello Mike, I\'ve reviewed your account and found that it was suspended due to suspicious activity. After further investigation, I can confirm this was a false positive. Your account has been restored and you should have full access now.',
            'type' => 'admin',
            'created_at' => '2025-01-22 14:20:00',
            'attachments' => []
        ],
        [
            'id' => 3,
            'ticket_id' => 'T003',
            'user_id' => 3,
            'username' => 'mike_wilson',
            'message' => 'Thank you for resolving this quickly. I can access my account now.',
            'type' => 'user',
            'created_at' => '2025-01-22 15:30:00',
            'attachments' => []
        ],
        [
            'id' => 4,
            'ticket_id' => 'T003',
            'user_id' => 0,
            'username' => 'admin',
            'message' => 'You\'re welcome! I\'ve also added a credit to your account as compensation for the inconvenience. This ticket is now resolved.',
            'type' => 'admin',
            'created_at' => '2025-01-22 17:30:00',
            'attachments' => []
        ]
    ]
];

// Load tickets from file
function loadTickets() {
    global $tickets_file, $default_tickets;
    
    if (file_exists($tickets_file)) {
        $content = file_get_contents($tickets_file);
        $tickets = json_decode($content, true);
        if ($tickets === null) {
            return $default_tickets;
        }
        return $tickets;
    }
    
    return $default_tickets;
}

// Save tickets to file
function saveTickets($tickets) {
    global $tickets_file;
    
    $json = json_encode($tickets, JSON_PRETTY_PRINT);
    $result = file_put_contents($tickets_file, $json);
    
    return $result !== false;
}

// Load messages from file
function loadMessages() {
    global $messages_file, $default_messages;
    
    if (file_exists($messages_file)) {
        $content = file_get_contents($messages_file);
        $messages = json_decode($content, true);
        if ($messages === null) {
            return $default_messages;
        }
        return $messages;
    }
    
    return $default_messages;
}

// Save messages to file
function saveMessages($messages) {
    global $messages_file;
    
    $json = json_encode($messages, JSON_PRETTY_PRINT);
    $result = file_put_contents($messages_file, $json);
    
    return $result !== false;
}

// Generate new ticket ID
function generateTicketId() {
    $tickets = loadTickets();
    $maxId = 0;
    
    foreach ($tickets as $ticket) {
        $id = intval(substr($ticket['id'], 1));
        if ($id > $maxId) {
            $maxId = $id;
        }
    }
    
    return 'T' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);
}

// API Endpoints
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        $tickets = loadTickets();
        $status = $_GET['status'] ?? 'all';
        $priority = $_GET['priority'] ?? 'all';
        $category = $_GET['category'] ?? 'all';
        
        // Filter tickets
        $filteredTickets = array_filter($tickets, function($ticket) use ($status, $priority, $category) {
            $statusMatch = $status === 'all' || $ticket['status'] === $status;
            $priorityMatch = $priority === 'all' || $ticket['priority'] === $priority;
            $categoryMatch = $category === 'all' || $ticket['category'] === $category;
            
            return $statusMatch && $priorityMatch && $categoryMatch;
        });
        
        $response = [
            'success' => true,
            'data' => array_values($filteredTickets),
            'total' => count($filteredTickets),
            'filters' => [
                'status' => $status,
                'priority' => $priority,
                'category' => $category
            ]
        ];
        break;
        
    case 'get':
        $ticketId = $_GET['ticket_id'] ?? '';
        $tickets = loadTickets();
        $messages = loadMessages();
        
        $ticket = null;
        foreach ($tickets as $t) {
            if ($t['id'] === $ticketId) {
                $ticket = $t;
                break;
            }
        }
        
        if ($ticket) {
            $ticket['messages'] = $messages[$ticketId] ?? [];
            $response = [
                'success' => true,
                'data' => $ticket
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Ticket not found'
            ];
        }
        break;
        
    case 'create':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $response = [
                'success' => false,
                'message' => 'Invalid JSON data'
            ];
            break;
        }
        
        $tickets = loadTickets();
        $messages = loadMessages();
        
        $newTicket = [
            'id' => generateTicketId(),
            'user_id' => $input['user_id'] ?? 1,
            'username' => $input['username'] ?? 'user',
            'subject' => $input['subject'] ?? 'New Ticket',
            'priority' => $input['priority'] ?? 'medium',
            'status' => 'open',
            'category' => $input['category'] ?? 'general',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'assigned_to' => null,
            'last_reply' => date('Y-m-d H:i:s'),
            'replies_count' => 0
        ];
        
        $tickets[] = $newTicket;
        
        // Add initial message
        $messages[$newTicket['id']] = [
            [
                'id' => 1,
                'ticket_id' => $newTicket['id'],
                'user_id' => $newTicket['user_id'],
                'username' => $newTicket['username'],
                'message' => $input['message'] ?? 'New ticket created',
                'type' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'attachments' => []
            ]
        ];
        
        if (saveTickets($tickets) && saveMessages($messages)) {
            $response = [
                'success' => true,
                'message' => 'Ticket created successfully',
                'data' => $newTicket
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to create ticket'
            ];
        }
        break;
        
    case 'reply':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $response = [
                'success' => false,
                'message' => 'Invalid JSON data'
            ];
            break;
        }
        
        $ticketId = $input['ticket_id'] ?? '';
        $message = $input['message'] ?? '';
        $type = $input['type'] ?? 'admin';
        $username = $input['username'] ?? 'admin';
        
        if (empty($ticketId) || empty($message)) {
            $response = [
                'success' => false,
                'message' => 'Missing required fields'
            ];
            break;
        }
        
        $tickets = loadTickets();
        $messages = loadMessages();
        
        // Find and update ticket
        $ticketFound = false;
        foreach ($tickets as &$ticket) {
            if ($ticket['id'] === $ticketId) {
                $ticket['updated_at'] = date('Y-m-d H:i:s');
                $ticket['last_reply'] = date('Y-m-d H:i:s');
                $ticket['replies_count']++;
                $ticketFound = true;
                break;
            }
        }
        
        if (!$ticketFound) {
            $response = [
                'success' => false,
                'message' => 'Ticket not found'
            ];
            break;
        }
        
        // Add message
        if (!isset($messages[$ticketId])) {
            $messages[$ticketId] = [];
        }
        
        $newMessage = [
            'id' => count($messages[$ticketId]) + 1,
            'ticket_id' => $ticketId,
            'user_id' => $type === 'admin' ? 0 : ($input['user_id'] ?? 1),
            'username' => $username,
            'message' => $message,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s'),
            'attachments' => $input['attachments'] ?? []
        ];
        
        $messages[$ticketId][] = $newMessage;
        
        if (saveTickets($tickets) && saveMessages($messages)) {
            $response = [
                'success' => true,
                'message' => 'Reply added successfully',
                'data' => $newMessage
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to add reply'
            ];
        }
        break;
        
    case 'update_status':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $response = [
                'success' => false,
                'message' => 'Invalid JSON data'
            ];
            break;
        }
        
        $ticketId = $input['ticket_id'] ?? '';
        $status = $input['status'] ?? '';
        $priority = $input['priority'] ?? '';
        $assignedTo = $input['assigned_to'] ?? '';
        
        if (empty($ticketId)) {
            $response = [
                'success' => false,
                'message' => 'Missing ticket ID'
            ];
            break;
        }
        
        $tickets = loadTickets();
        
        $ticketFound = false;
        foreach ($tickets as &$ticket) {
            if ($ticket['id'] === $ticketId) {
                if (!empty($status)) {
                    $ticket['status'] = $status;
                }
                if (!empty($priority)) {
                    $ticket['priority'] = $priority;
                }
                if (!empty($assignedTo)) {
                    $ticket['assigned_to'] = $assignedTo;
                }
                $ticket['updated_at'] = date('Y-m-d H:i:s');
                $ticketFound = true;
                break;
            }
        }
        
        if (!$ticketFound) {
            $response = [
                'success' => false,
                'message' => 'Ticket not found'
            ];
            break;
        }
        
        if (saveTickets($tickets)) {
            $response = [
                'success' => true,
                'message' => 'Ticket updated successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to update ticket'
            ];
        }
        break;
        
    case 'delete':
        $ticketId = $_GET['ticket_id'] ?? '';
        
        if (empty($ticketId)) {
            $response = [
                'success' => false,
                'message' => 'Missing ticket ID'
            ];
            break;
        }
        
        $tickets = loadTickets();
        $messages = loadMessages();
        
        // Remove ticket
        $tickets = array_filter($tickets, function($ticket) use ($ticketId) {
            return $ticket['id'] !== $ticketId;
        });
        
        // Remove messages
        unset($messages[$ticketId]);
        
        if (saveTickets($tickets) && saveMessages($messages)) {
            $response = [
                'success' => true,
                'message' => 'Ticket deleted successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to delete ticket'
            ];
        }
        break;
        
    case 'stats':
        $tickets = loadTickets();
        
        $stats = [
            'total' => count($tickets),
            'open' => 0,
            'pending' => 0,
            'resolved' => 0,
            'high_priority' => 0,
            'medium_priority' => 0,
            'low_priority' => 0,
            'unassigned' => 0
        ];
        
        foreach ($tickets as $ticket) {
            // Status counts
            if ($ticket['status'] === 'open') $stats['open']++;
            elseif ($ticket['status'] === 'pending') $stats['pending']++;
            elseif ($ticket['status'] === 'resolved') $stats['resolved']++;
            
            // Priority counts
            if ($ticket['priority'] === 'high') $stats['high_priority']++;
            elseif ($ticket['priority'] === 'medium') $stats['medium_priority']++;
            elseif ($ticket['priority'] === 'low') $stats['low_priority']++;
            
            // Assignment counts
            if (empty($ticket['assigned_to'])) $stats['unassigned']++;
        }
        
        $response = [
            'success' => true,
            'data' => $stats
        ];
        break;
        
    default:
        $response = [
            'success' => false,
            'message' => 'Invalid action',
            'available_actions' => [
                'list',
                'get',
                'create',
                'reply',
                'update_status',
                'delete',
                'stats'
            ]
        ];
        break;
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
