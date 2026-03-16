<?php
require_once __DIR__ . '/functions.php';

class Auth {
    public static function login($username, $password, $code) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = ? AND code_login = ?");
        $stmt->execute([$username, $code]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) {
            return ['success' => false, 'message' => 'Username atau code salah'];
        }
        
        if (!password_verify($password, $admin['password'])) {
            return ['success' => false, 'message' => 'Password salah'];
        }
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        return ['success' => true];
    }
    
    public static function check() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: index.php');
            exit;
        }
    }
    
    public static function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>
