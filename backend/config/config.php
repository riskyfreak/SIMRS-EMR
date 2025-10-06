<?php
define('ROOT_PATH', realpath(__DIR__ . '/../..'));                      // project root
define('BACKEND_PATH', realpath(__DIR__ .'/..'));                       // backend/
define('BACKEND_MODELS_PATH', BACKEND_PATH . '/models');                // backend/models/
define('BACKEND_API_PATH', BACKEND_PATH . '/api');                      // backend/api/
define('FRONTEND_PATH', realpath(__DIR__ . '/../../frontend'));         // frontend/
define('FRONTEND_CONTROLLERS_PATH', FRONTEND_PATH . '/controllers');    // frontend/controllers/
define('FRONTEND_VIEWS_PATH', FRONTEND_PATH . '/views');                // frontend/views/

// Database config
require_once BACKEND_PATH . '/config/database.php';

if (file_exists(BACKEND_MODELS_PATH . '/Database.php')) {
    require_once BACKEND_MODELS_PATH . '/Database.php';
}

// session & timezone
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Jakarta');

// Base URL and DB constants (ubah sesuai environment)
define('BASE_URL', 'http://192.168.0.25/SIMRS-EMR/');
define('DB_HOST', DatabaseConfig::$host);
define('DB_USER', DatabaseConfig::$username);
define('DB_PASS', DatabaseConfig::$password);
define('DB_NAME', DatabaseConfig::$database);

// Autoload controllers (backend/controllers)
spl_autoload_register(function ($class_name) {
    $tryPaths = [
        BACKEND_MODELS_PATH . '/' . $class_name . '.php',
        FRONTEND_CONTROLLERS_PATH . '/' . $class_name . '.php',
        BACKEND_PATH . '/controllers/' . $class_name . '.php', // fallback
        FRONTEND_PATH . '/models/' . $class_name . '.php' // in case some left
    ];

    foreach ($tryPaths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Helper functions
function redirect($url) {
    header("Location: $url");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasRole($allowed_roles) {
    if (!isLoggedIn()) return false;
    return in_array($_SESSION['role'], (array)$allowed_roles);
}
?>
