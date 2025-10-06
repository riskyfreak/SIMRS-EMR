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
define('BASE_URL', 'http://localhost/SIMRS-EMR/');
define('DB_HOST', DatabaseConfig::$host);
define('DB_USER', DatabaseConfig::$username);
define('DB_PASS', DatabaseConfig::$password);
define('DB_NAME', DatabaseConfig::$database);

spl_autoload_register(function($class_name){
    $files = [
        BACKEND_MODELS_PATH . "/$class_name.php",
        FRONTEND_PATH . "/controllers/$class_name.php",
    ];
    foreach($files as $f) if (file_exists($f)) { require_once $f; return; }
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

    // sesi menyimpan jabatan di key 'jabatan' (sesuai UserModel::createUserSession)
    $current = isset($_SESSION['jabatan']) ? strtolower($_SESSION['jabatan']) : null;
    if (!$current) return false;

    // allow both string and array for $allowed_roles
    $allowed = is_array($allowed_roles) ? $allowed_roles : [$allowed_roles];
    $allowed = array_map('strtolower', $allowed);

    return in_array($current, $allowed);
}
?>
