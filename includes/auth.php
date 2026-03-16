<?php
require_once 'functions.php';

class Auth {
    public static function register($username, $password) {
        $db = Database::getInstance();
        
        // Check if any user exists
        $stmt = $db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $isFirst = $result['total'] == 0;
        
        // Check if username exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username sudah dipakai'];
        }
        
        // Create user
        $code = generateCode();
        $expDate = getExpDate(7); // Trial 7 hari
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (username, password, code_login, mode, exp_date, is_admin) VALUES (?, ?, ?, 'TRIAL', ?, ?)");
        $stmt->execute([$username, $hashedPass, $code, $expDate, $isFirst ? 1 : 0]);
        
        return [
            'success' => true,
            'message' => 'Registrasi berhasil',
            'code' => $code,
            'is_admin' => $isFirst ? 1 : 0
        ];
    }
    
    public static function login($username, $password, $code) {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND code_login = ?");
        $stmt->execute([$username, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Username/password/code salah'];
        }
        
        if (isExpired($user['exp_date'])) {
            return ['success' => false, 'message' => 'Akun expired', 'expired' => true];
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        return ['success' => true, 'user' => $user];
    }
    
    public static function getUser($id = null) {
        if (!$id && isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
        }
        
        if (!$id) return null;
        
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function logout() {
        session_destroy();
    }
    
    public static function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
}
?>
