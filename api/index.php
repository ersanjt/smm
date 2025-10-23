<?php
// Vercel PHP API endpoint
header('Content-Type: text/html; charset=utf-8');

// Set environment
define('ENVIRONMENT', 'production');

// Error reporting
if (ENVIRONMENT == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Define paths
$system_path = '../system';
$application_folder = '../application';

// Check if system path exists
if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path).'/';
}

$system_path = rtrim($system_path, '/').'/';

// Check if application folder exists
if (is_dir($application_folder)) {
    $application_folder = realpath($application_folder);
}

// Define constants
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace("\\", "/", $system_path));
define('FCPATH', dirname(__FILE__).'/');
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
define('APPPATH', $application_folder.'/');

// Check if CodeIgniter exists
if (!is_dir(BASEPATH)) {
    http_response_code(500);
    die('CodeIgniter system directory not found. Please check your installation.');
}

// Bootstrap CodeIgniter
require_once BASEPATH.'core/CodeIgniter.php';
