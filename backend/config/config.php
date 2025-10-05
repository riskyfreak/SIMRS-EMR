<?php
// Konfigurasi dasar aplikasi
require_once 'config/database.php';
require_once 'models/Database.php';
require_once 'models/UserModel.php';

session_start();
date_default_timezone_set('Asia/Jakarta');

define('BASE_URL', 'http://192.168.0.25/simrs/');
define('DB_HOST', '192.168.0.25');
define('DB_USER', 'develop');
define('DB_PASS', 'sarimulia139@');
define('DB_NAME', 'simrs_db');

// Auto load classes
spl_autoload_register(function ($class_name) {
    include 'controllers/' . $class_name . '.php';
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
    return in_array($_SESSION['role'], $allowed_roles);
}
?>