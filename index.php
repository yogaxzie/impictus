<?php
session_start();
require_once 'includes/auth.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $code = $_POST['code'];
    
    $result = Auth::login($username, $password, $code);
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Moon Soul 4.5 · Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        
        body {
            background: #0a0f1e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 380px;
            background: #141b2b;
            border-radius: 40px;
            padding: 35px 25px;
            border: 1px solid #1e2a3a;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }
        
        .logo {
            font-size: 38px;
            font-weight: 800;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
            text-align: center;
        }
        
        .version {
            color: #6b7280;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            letter-spacing: 1px;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            margin-left: 5px;
        }
        
        .input-group input {
            width: 100%;
            padding: 16px 20px;
            background: #1e293b;
            border: 1.5px solid #2d3a4f;
            border-radius: 30px;
            color: white;
            font-size: 15px;
            transition: 0.2s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #a855f7;
        }
        
        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            border: none;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
        
        .error-message {
            background: #2f1a1a;
            color: #ef4444;
            padding: 15px;
            border-radius: 20px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #ef4444;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            color: #4b5563;
            font-size: 12px;
        }
        
        .moon-icon {
            text-align: center;
            font-size: 50px;
            margin-bottom: 5px;
            filter: drop-shadow(0 0 20px #a855f7);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="moon-icon">🌙</div>
        <div class="logo">MOON SOUL</div>
        <div class="version">v4.5 · ADMIN PANEL</div>
        
        <?php if ($error): ?>
        <div class="error-message">⚠️ <?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>👤 USERNAME</label>
                <input type="text" name="username" placeholder="Moon4.5" value="Moon4.5" required>
            </div>
            
            <div class="input-group">
                <label>🔑 PASSWORD</label>
                <input type="password" name="password" placeholder="••••••••" value="soul4.5" required>
            </div>
            
            <div class="input-group">
                <label>🔐 CODE LOGIN</label>
                <input type="text" name="code" placeholder="Cv27soul" value="Cv27soul" required>
            </div>
            
            <button type="submit" class="login-btn">🚀 ACCESS PANEL</button>
        </form>
        
        <div class="footer">
            Moon Soul 4.5 · Private Panel<br>
            ⚡ Only for authorized personnel
        </div>
    </div>
</body>
</html>
