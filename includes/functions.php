<?php
function generateLoginCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function getUserData($user_id, $conn) {
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

function isPremium($user_id, $conn) {
    $user = getUserData($user_id, $conn);
    if ($user['is_admin']) return true;
    if ($user['is_premium']) {
        if (strtotime($user['expired_date']) > time()) {
            return true;
        } else {
            // Auto downgrade kalo expired
            $conn->query("UPDATE users SET is_premium = FALSE WHERE id = '$user_id'");
            return false;
        }
    }
    return false;
}
?>
