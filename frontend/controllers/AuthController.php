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
        redirect(BASE_URL . 'auth/index.php');
    }

    private function showLoginPage($error = null) {
        include FRONTEND_VIEWS_PATH . '/auth/index.php';
    }

    private function redirectBasedOnRole($jabatan) {
        $roleRoutes = [
            'admin' => 'dashboard.php',
            'dokter' => 'dokter/dashboard.php',
            'perawat' => 'perawat/dashboard.php',
            'pendaftaran' => 'pendaftaran/dashboard.php',
            'farmasi' => 'farmasi/dashboard.php',
            'keuangan' => 'keuangan/dashboard.php'
        ];

        $defaultRoute = $roleRoutes[$jabatan] ?? 'dashboard.php';
        redirect(BASE_URL . $defaultRoute);
    }
}
?>