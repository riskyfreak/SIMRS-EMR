<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                $this->userModel->createUserSession($user);
                
                // Redirect based on role
                $this->redirectBasedOnRole($user['jabatan']);
            } else {
                $error = "Username atau password salah!";
                $this->showLoginPage($error);
            }
        } else {
            $this->showLoginPage();
        }
    }

    public function logout() {
        $this->userModel->logout();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    private function showLoginPage($error = null) {
        include FRONTEND_VIEWS_PATH . '/auth/index.php';
    }

    private function redirectBasedOnRole($jabatan) {
        $jabatanKey = strtolower(trim($jabatan));
        // Map jabatan -> page param
        $roleRoutes = [
            'admin' => 'dashboard',
            'dokter' => 'dokter/dashboard',
            'perawat' => 'perawat/dashboard',
            'pendaftaran' => 'pendaftaran/dashboard',
            'farmasi' => 'farmasi/dashboard',
            'keuangan' => 'keuangan/dashboard',
            'laboratorium' => 'laboratorium/dashboard',
        ];

        $route = $roleRoutes[$jabatanKey] ?? 'dashboard';
        // redirect ke URL bersih (tanpa index.php?page=...)
        redirect(BASE_URL . $route . '/');
    }
}
?>