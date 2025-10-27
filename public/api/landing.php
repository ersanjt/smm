<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../application/controllers/Landing.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get_content') {
    $landing = new Landing();
    $landing->get_content();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save_content') {
    $landing = new Landing();
    $landing->save_content();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
