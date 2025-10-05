<?php
class PatientController {
    private $patientModel;

    public function __construct() {
        $this->patientModel = new PatientModel();
    }

    public function index() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . 'index.php?page=auth&action=login');
        }

        $patients = $this->patientModel->getAll();
        require_once 'views/patient/index.php';
    }

    public function create() {
        if (!isLoggedIn()) {
            redirect(BASE_URL . 'index.php?page=auth&action=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();
            
            if ($this->patientModel->create($data)) {
                $_SESSION['success_message'] = 'Data pasien berhasil ditambahkan.';
                redirect(BASE_URL . 'index.php?page=patient&action=index');
            } else {
                $error = "Gagal menambahkan data pasien.";
                require_once 'views/patient/create.php';
            }
        } else {
            require_once 'views/patient/create.php';
        }
    }

    public function edit($noRekamMedis) {
        if (!isLoggedIn()) {
            redirect(BASE_URL . 'index.php?page=auth&action=login');
        }

        $patient = $this->patientModel->getByNoRekamMedis($noRekamMedis);

        if (!$patient) {
            $_SESSION['error_message'] = 'Data pasien tidak ditemukan.';
            redirect(BASE_URL . 'index.php?page=patient&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();
            
            if ($this->patientModel->update($noRekamMedis, $data)) {
                $_SESSION['success_message'] = 'Data pasien berhasil diperbarui.';
                redirect(BASE_URL . 'index.php?page=patient&action=index');
            } else {
                $error = "Gagal memperbarui data pasien.";
                require_once 'views/patient/edit.php';
            }
        } else {
            require_once 'views/patient/edit.php';
        }
    }

    public function delete($noRekamMedis) {
        if (!isLoggedIn()) {
            redirect(BASE_URL . 'index.php?page=auth&action=login');
        }

        if ($this->patientModel->delete($noRekamMedis)) {
            $_SESSION['success_message'] = 'Data pasien berhasil dihapus.';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus data pasien.';
        }

        redirect(BASE_URL . 'index.php?page=patient&action=index');
    }

    private function getFormData() {
        return [
            'no_rekam_medis' => $_POST['no_rekam_medis'],
            'nik' => $_POST['nik'],
            'nama_pasien' => $_POST['nama_pasien'],
            'tempat_lahir' => $_POST['tempat_lahir'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'golongan_darah' => $_POST['golongan_darah'],
            'agama' => $_POST['agama'],
            'status_perkawinan' => $_POST['status_perkawinan'],
            'pendidikan' => $_POST['pendidikan'],
            'pekerjaan' => $_POST['pekerjaan'],
            'alamat' => $_POST['alamat'],
            'kd_kelurahan' => $_POST['kd_kelurahan'],
            'kd_kecamatan' => $_POST['kd_kecamatan'],
            'kd_kabupaten' => $_POST['kd_kabupaten'],
            'kd_provinsi' => $_POST['kd_provinsi'],
            'no_telepon' => $_POST['no_telepon'],
            'no_kartu' => $_POST['no_kartu'],
            'nama_ibu' => $_POST['nama_ibu'],
            'suku_bangsa' => $_POST['suku_bangsa'],
            'bahasa' => $_POST['bahasa'],
            'nama_penanggung_jawab' => $_POST['nama_penanggung_jawab'],
            'hubungan_dengan_pasien' => $_POST['hubungan_dengan_pasien'],
            'alamat_penanggung_jawab' => $_POST['alamat_penanggung_jawab'],
            'kd_kelurahan_penanggung_jawab' => $_POST['kd_kelurahan_penanggung_jawab'],
            'kd_kecamatan_penanggung_jawab' => $_POST['kd_kecamatan_penanggung_jawab'],
            'kd_kabupaten_penanggung_jawab' => $_POST['kd_kabupaten_penanggung_jawab'],
            'kd_provinsi_penanggung_jawab' => $_POST['kd_provinsi_penanggung_jawab'],
            'no_telepon_penanggung_jawab' => $_POST['no_telepon_penanggung_jawab'],
            'email' => $_POST['email']
        ];
    }
}
?>