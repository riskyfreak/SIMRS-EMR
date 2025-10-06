<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

// now models are in backend/models
require_once BACKEND_MODELS_PATH . '/UserModel.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$response = ['success' => false, 'message' => ''];
$patientModel = new PatientModel();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['no_rekam_medis'])) {
            $patient = $patientModel->getByNoRekamMedis($_GET['no_rekam_medis']);
            if ($patient) {
                $response['success'] = true;
                $response['data'] = $patient;
            } else {
                $response['message'] = 'Pasien tidak ditemukan';
            }
        } else {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $patients = $patientModel->getAll($page);
            $response['success'] = true;
            $response['data'] = $patients;
            $response['pagination'] = [
                'page' => (int)$page,
                'total' => (int)$patientModel->getTotalCount()
            ];
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $response['message'] = 'Payload invalid';
            break;
        }
        $no_rm = $patientModel->create($input);

        if ($no_rm) {
            $response['success'] = true;
            $response['message'] = 'Pasien berhasil didaftarkan';
            $response['data'] = ['no_rekam_medis' => $no_rm];
        } else {
            $response['message'] = 'Gagal mendaftarkan pasien';
        }
        break;

    default:
        $response['message'] = 'Method tidak diizinkan';
        break;
}

echo json_encode($response);
?>