<?php
// FUNCTIONS BUILDERZ
require_once __DIR__ . '/../config/database.php';

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME,
                DB_USER,
                DB_PASS
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

function generateCode() {
    return 'BZ' . strtoupper(substr(md5(uniqid()), 0, 8));
}

function isExpired($expDate) {
    return strtotime($expDate) < time();
}

function getExpDate($days = 7) {
    return date('Y-m-d', strtotime("+$days days"));
}

function createSite($userId, $code) {
    $db = Database::getInstance();
    $url = 'https://builderz.my.id/' . uniqid();
    
    $stmt = $db->prepare("INSERT INTO websites (user_id, site_url, site_code) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $url, $code]);
    
    return $url;
}

function getUserSites($userId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM websites WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
