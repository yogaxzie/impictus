<?php
session_start();
require_once 'includes/auth.php';

$error = '';
$success = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $result = Auth::login($_POST['username'], $_POST['password'], $_POST['code']);
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['message'];
    }
}

// Handle Register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $result = Auth::register($_POST['reg_username'], $_POST['reg_password']);
    if ($result['success']) {
        $success = "Registrasi berhasil! Code login kamu: <strong>" . $result['code'] . "</strong>";
        if ($result['is_admin']) {
            $success .= "<br>⚠️ Kamu adalah ADMIN pertama!";
        }
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
    <title>BuilderZ · mobile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, system-ui, sans-serif;
        }
        
        body {
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        
        .phone-frame {
            width: 100%;
            max-width: 380px;
            background: white;
            border-radius: 40px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            padding: 24px 20px;
            min-height: 600px;
            position: relative;
        }
        
        .logo {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #4158D0, #C850C0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        
        .sub {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 32px;
            border-left: 3px solid #4158D0;
            padding-left: 12px;
        }
        
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }
        
        .tab-btn {
            flex: 1;
            padding: 14px;
            border: none;
            background: #f1f5f9;
            border-radius: 20px;
            font-weight: 600;
            font-size: 16px;
            color: #64748b;
            cursor: pointer;
            transition: 0.2s;
        }
        
        .tab-btn.active {
            background: #4158D0;
            color: white;
        }
        
        .form-container {
            transition: 0.3s;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 18px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            font-size: 15px;
            transition: 0.2s;
        }
        
        .form-group input:focus {
            border-color: #4158D0;
            outline: none;
            background: white;
        }
        
        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            background: #4158D0;
            color: white;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        
        .btn:hover {
            transform: scale(0.98);
            background: #3649b3;
        }
        
        .message {
            padding: 15px;
            border-radius: 20px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .success {
            background: #d1fae5;
            color: #059669;
            border-left: 4px solid #059669;
        }
        
        .info-text {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 20px;
            text-align: center;
        }
        
        .hidden {
            display: none !important;
        }
        
        .register-info {
            background: #f0f9ff;
            padding: 16px;
            border-radius: 20px;
            margin-top: 20px;
            font-size: 13px;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <div class="logo">BuilderZ</div>
        <div class="sub">build anything · instant</div>
        
        <div class="tab-buttons">
            <button class="tab-btn active" id="tabLogin" onclick="switchTab('login')">Masuk</button>
            <button class="tab-btn" id="tabRegister" onclick="switchTab('register')">Daftar</button>
        </div>
        
        <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
        <?php endif; ?>
        
        <!-- LOGIN FORM -->
        <div id="loginForm" class="form-container">
            <form method="POST">
                <input type="hidden" name="login" value="1">
                
                <div class="form-group">
                    <label>👤 Username</label>
                    <input type="text" name="username" placeholder="masukin username" required>
                </div>
                
                <div class="form-group">
                    <label>🔑 Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                
                <div class="form-group">
                    <label>🔐 Code Login</label>
                    <input type="text" name="code" placeholder="kode rahasia" required>
                </div>
                
                <button type="submit" class="btn">MASUK</button>
            </form>
            
            <div class="info-text">
                Belum punya akun? <strong onclick="switchTab('register')" style="color:#4158D0;">Daftar dulu</strong>
            </div>
        </div>
        
        <!-- REGISTER FORM -->
        <div id="registerForm" class="form-container hidden">
            <form method="POST">
                <input type="hidden" name="register" value="1">
                
                <div class="form-group">
                    <label>👤 Username</label>
                    <input type="text" name="reg_username" placeholder="min 3 karakter" required>
                </div>
                
                <div class="form-group">
                    <label>🔑 Password</label>
                    <input type="password" name="reg_password" placeholder="min 4 karakter" required>
                </div>
                
                <button type="submit" class="btn">DAFTAR</button>
            </form>
            
            <div class="register-info">
                <strong>ℹ️ Info Penting:</strong><br>
                • User pertama otomatis jadi ADMIN<br>
                • Code login akan digenerate otomatis<br>
                • Masa trial 7 hari<br>
                • Simpan code login baik-baik
            </div>
            
            <div class="info-text" style="margin-top:15px;">
                Sudah punya akun? <strong onclick="switchTab('login')" style="color:#4158D0;">Masuk</strong>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tab) {
            if (tab === 'login') {
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('registerForm').classList.add('hidden');
                document.getElementById('tabLogin').classList.add('active');
                document.getElementById('tabRegister').classList.remove('active');
            } else {
                document.getElementById('loginForm').classList.add('hidden');
                document.getElementById('registerForm').classList.remove('hidden');
                document.getElementById('tabLogin').classList.remove('active');
                document.getElementById('tabRegister').classList.add('active');
            }
        }
    </script>
</body>
</html>
