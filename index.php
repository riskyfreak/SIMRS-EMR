<?php
require_once __DIR__ . '/backend/config/config.php';

// Ambil path yang diminta, tanpa query string
$basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '/SIMRS-EMR/';
$uri = $_SERVER['REQUEST_URI'];

// Hapus base path (jika situs tidak di root)
if (strpos($uri, $basePath) === 0) {
    $path = substr($uri, strlen($basePath));
} else {
    // fallback: hapus leading slash
    $path = ltrim($uri, '/');
}

// Hilangkan query string jika masih ada
$path = strtok($path, '?');
$path = trim($path, '/'); // e.g. 'dashboard' atau '' untuk root

// Map path ke page/action
if ($path === '' || $path === 'index.php') {
    // root -> cek session: jika login redirect ke dashboard, else show login
    if (isLoggedIn()) {
        $page = 'dashboard';
        $action = 'index';
    } else {
        $page = 'auth';
        $action = 'login';
    }
} else {
    $segments = explode('/', $path);
    $page = $segments[0] ?? 'dashboard';
    $action = $segments[1] ?? 'index';
}

// Simple router: gunakan controller di frontend/controllers
switch ($page) {
    case 'auth':
        require_once FRONTEND_CONTROLLERS_PATH . '/AuthController.php';
        $authController = new AuthController();
        if ($action === 'login') $authController->login();
        elseif ($action === 'logout') $authController->logout();
        else $authController->login();
        break;

    case 'patient':
    case 'pasien':
        require_once FRONTEND_CONTROLLERS_PATH . '/PatientController.php';
        $patientController = new PatientController();
        if ($action === 'index') $patientController->index();
        elseif ($action === 'create') $patientController->create();
        elseif ($action === 'edit') {
            $id = $segments[2] ?? null;
            $patientController->edit($id);
        } elseif ($action === 'delete') {
            $id = $segments[2] ?? null;
            $patientController->delete($id);
        } else {
            $patientController->index();
        }
        break;

    case 'dashboard':
        require_once FRONTEND_VIEWS_PATH . '/dashboard/index.php';
        break;

    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan";
}
