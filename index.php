<?php
require_once __DIR__ . '/backend/config/config.php';

// simple routing
$page = $_GET['page'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if ($page == 'auth') {
    $controllerFile = FRONTEND_CONTROLLERS_PATH . '/AuthController.php';
    if (file_exists($controllerFile)) require_once $controllerFile;
    $authController = new AuthController();
    if ($action == 'login') $authController->login();
    elseif ($action == 'logout') $authController->logout();
    else $authController->login();
} elseif ($page == 'patient') {
    $controllerFile = FRONTEND_CONTROLLERS_PATH . '/PatientController.php';
    if (file_exists($controllerFile)) require_once $controllerFile;
    $patientController = new PatientController();
    $action = $_GET['action'] ?? 'index';
    $id = $_GET['id'] ?? null;
    if ($action == 'index') $patientController->index();
    elseif ($action == 'create') $patientController->create();
    elseif ($action == 'edit' && $id) $patientController->edit($id);
    elseif ($action == 'delete' && $id) $patientController->delete($id);
    else $patientController->index();
} elseif ($page == 'dashboard') {
    require_once FRONTEND_VIEWS_PATH . '/dashboard/index.php';
} else {
    http_response_code(404);
    echo "Halaman tidak ditemukan";
}
