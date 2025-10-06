<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

// now models are in backend/models
require_once BACKEND_MODELS_PATH . '/UserModel.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    $userModel = new UserModel();
    $user = $userModel->authenticate($username, $password);

    if ($user) {
        $response['success'] = true;
        $response['message'] = 'Login berhasil';
        $response['data'] = [
            'user_id' => (int)$user['id'],
            'username' => $user['username'],
            'nama_pegawai' => $user['nama_pegawai'],
            'jabatan' => $user['jabatan'],
            'unit_kerja' => $user['unit_kerja'],
            'pegawai_nip' => $user['pegawai_nip']
        ];
    } else {
        $response['message'] = 'Username atau password salah';
    }
} else {
    $response['message'] = 'Method tidak diizinkan';
}

echo json_encode($response);
