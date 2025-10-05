<?php
require_once 'config/config.php';

// Routing sederhana
$page = $_GET['page'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Route mapping
if ($page == 'auth') {
    $authController = new AuthController();
    if ($action == 'login') {
        $authController->login();
    } elseif ($action == 'logout') {
        $authController->logout();
    } else {
        // Default ke login
        $authController->login();
    }
} elseif ($page == 'patient') {
    require_once 'controllers/PatientController.php';
    $patientController = new PatientController();
    
    $action = $_GET['action'] ?? 'index';
    $id = $_GET['id'] ?? null;
    
    if ($action == 'index') {
        $patientController->index();
    } elseif ($action == 'create') {
        $patientController->create();
    } elseif ($action == 'edit' && $id) {
        $patientController->edit($id);
    } elseif ($action == 'delete' && $id) {
        $patientController->delete($id);
    } else {
        // Default ke index
        $patientController->index();
    }
} elseif ($page == 'dashboard') {
    require_once 'views/dashboard/index.php';
} else {
    // Page not found
    http_response_code(404);
    echo "Halaman tidak ditemukan";
}
?>