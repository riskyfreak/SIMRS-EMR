<?php
class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function authenticate($username, $password) {
        $query = "SELECT user.*, pegawai.nama_pegawai, pegawai.jabatan, pegawai.unit_kerja 
                 FROM user
                 JOIN pegawai ON user.pegawai_nip = pegawai.nip 
                 WHERE user.username = :username AND pegawai.status_aktif = 1";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }
    
    private function updateLastLogin($userId) {
        $query = "UPDATE user SET last_login = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_pegawai'] = $user['nama_pegawai'];
        $_SESSION['jabatan'] = $user['jabatan'];
        $_SESSION['unit_kerja'] = $user['unit_kerja'];
        $_SESSION['pegawai_nip'] = $user['pegawai_nip'];
    }
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy(); 
    }
}
?>