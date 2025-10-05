<?php
header('Content-Type: application/json');
require_once '../config/config.php';

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$response = ['success' => false, 'message' => ''];
$patientModel = new PatientModel();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['no_rekam_medis'])) {
            // Get single patient
            $patient = $patientModel->getByNoRekamMedis($_GET['no_rekam_medis']);
            if ($patient) {
                $response['success'] = true;
                $response['data'] = $patient;
            } else {
                $response['message'] = 'Pasien tidak ditemukan';
            }
        } else {
            // Get all patients
            $page = $_GET['page'] ?? 1;
            $patients = $patientModel->getAll($page);
            $response['success'] = true;
            $response['data'] = $patients;
            $response['pagination'] = [
                'page' => (int)$page,
                'total' => $patientModel->getTotalCount()
            ];
        }
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
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