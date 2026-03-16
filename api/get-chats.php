<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$victimId = $_GET['victim_id'];
$chats = getVictimChats($victimId);
markChatsAsRead($victimId);

echo json_encode(['success' => true, 'chats' => $chats]);
?>
