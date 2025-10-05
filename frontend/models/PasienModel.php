<?php
class PatientModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Create new patient
    public function create($data) {
        // Generate nomor rekam medis otomatis
        $no_rekam_medis = $this->generateNoRekamMedis();
        $data['no_rekam_medis'] = $no_rekam_medis;
        
        $query = "INSERT INTO pasien (
            no_rekam_medis, nik, nama_pasien, tempat_lahir, tanggal_lahir, 
            jenis_kelamin, golongan_darah, agama, status_perkawinan, pendidikan, 
            pekerjaan, alamat, kd_kelurahan, kd_kecamatan, kd_kabupaten, kd_provinsi, 
            no_telepon, no_kartu, nama_ibu, suku_bangsa, bahasa, 
            nama_penanggung_jawab, hubungan_dengan_pasien, alamat_penanggung_jawab, 
            kd_kelurahan_penanggung_jawab, kd_kecamatan_penanggung_jawab, 
            kd_kabupaten_penanggung_jawab, kd_provinsi_penanggung_jawab, 
            no_telepon_penanggung_jawab, email, created_at
        ) VALUES (
            :no_rekam_medis, :nik, :nama_pasien, :tempat_lahir, :tanggal_lahir, 
            :jenis_kelamin, :golongan_darah, :agama, :status_perkawinan, :pendidikan, 
            :pekerjaan, :alamat, :kd_kelurahan, :kd_kecamatan, :kd_kabupaten, :kd_provinsi, 
            :no_telepon, :no_kartu, :nama_ibu, :suku_bangsa, :bahasa, 
            :nama_penanggung_jawab, :hubungan_dengan_pasien, :alamat_penanggung_jawab, 
            :kd_kelurahan_penanggung_jawab, :kd_kecamatan_penanggung_jawab, 
            :kd_kabupaten_penanggung_jawab, :kd_provinsi_penanggung_jawab, 
            :no_telepon_penanggung_jawab, :email, NOW()
        )";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data) ? $no_rekam_medis : false;
    }
    
    // Get all patients with pagination
    public function getAll($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM pasien 
                 ORDER BY created_at DESC 
                 LIMIT :limit OFFSET :offset";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get patient by no_rekam_medis
    public function getByNoRekamMedis($no_rekam_medis) {
        $query = "SELECT pasien.*, 
                 provinsi.nama_provinsi, kabupaten.nama_kabupaten, 
                 kecamatan.nama_kecamatan, kelurahan.nama_kelurahan
                 FROM pasien
                 LEFT JOIN provinsi ON pasien.kd_provinsi = provinsi.kd_provinsi
                 LEFT JOIN kabupaten ON pasien.kd_kabupaten = kabupaten.kd_kabupaten
                 LEFT JOIN kecamatan ON pasien.kd_kecamatan = kecamatan.kd_kecamatan
                 LEFT JOIN kelurahan ON pasien.kd_kelurahan = kelurahan.kd_kelurahan
                 WHERE pasien.no_rekam_medis = :no_rekam_medis";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':no_rekam_medis', $no_rekam_medis);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Update patient
    public function update($no_rekam_medis, $data) {
        $data['no_rekam_medis'] = $no_rekam_medis;
        
        $query = "UPDATE pasien SET 
            nik = :nik, nama_pasien = :nama_pasien, tempat_lahir = :tempat_lahir, 
            tanggal_lahir = :tanggal_lahir, jenis_kelamin = :jenis_kelamin, 
            golongan_darah = :golongan_darah, agama = :agama, status_perkawinan = :status_perkawinan, 
            pendidikan = :pendidikan, pekerjaan = :pekerjaan, alamat = :alamat, 
            kd_kelurahan = :kd_kelurahan, kd_kecamatan = :kd_kecamatan, 
            kd_kabupaten = :kd_kabupaten, kd_provinsi = :kd_provinsi, 
            no_telepon = :no_telepon, no_kartu = :no_kartu, nama_ibu = :nama_ibu, 
            suku_bangsa = :suku_bangsa, bahasa = :bahasa, 
            nama_penanggung_jawab = :nama_penanggung_jawab, 
            hubungan_dengan_pasien = :hubungan_dengan_pasien, 
            alamat_penanggung_jawab = :alamat_penanggung_jawab, 
            kd_kelurahan_penanggung_jawab = :kd_kelurahan_penanggung_jawab, 
            kd_kecamatan_penanggung_jawab = :kd_kecamatan_penanggung_jawab, 
            kd_kabupaten_penanggung_jawab = :kd_kabupaten_penanggung_jawab, 
            kd_provinsi_penanggung_jawab = :kd_provinsi_penanggung_jawab, 
            no_telepon_penanggung_jawab = :no_telepon_penanggung_jawab, 
            email = :email, updated_at = NOW()
            WHERE no_rekam_medis = :no_rekam_medis";
            
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    
    // Search patients
    public function search($keyword) {
        $query = "SELECT * FROM pasien 
                 WHERE nama_pasien LIKE :keyword 
                    OR nik LIKE :keyword 
                    OR no_rekam_medis LIKE :keyword
                 ORDER BY nama_pasien";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get total patients count
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM pasien";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch()['total'];
    }
    
    // Generate nomor rekam medis otomatis
    private function generateNoRekamMedis() {
        $year = date('Y');
        $month = date('m');
        
        // Format: RMYYYYMMXXX (contoh: RM202412001)
        $query = "SELECT COUNT(*) as count FROM pasien 
                 WHERE no_rekam_medis LIKE 'RM$year$month%'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $count = $stmt->fetch()['count'] + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "RM$year$month$sequence";
    }
}
?>