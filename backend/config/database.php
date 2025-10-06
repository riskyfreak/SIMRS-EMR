<?php
class DatabaseConfig {
    public static $host = "192.168.0.25";
    public static $username = "root";
    public static $password = ""; 
    public static $database = "simrs_db";
    public static $port = 3306;
    
    public static function getDSN() {
        return "mysql:host=" . self::$host . ";dbname=" . self::$database . ";charset=utf8mb4";
    }
}
?>
