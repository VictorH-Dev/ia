<?php
session_start();
include 'app/db.conn.php';
include 'app/helpers/user.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$groupId = $_GET['group_id'] ?? null;
function sairDoGrupo($groupId, $userId, $conn) {
    $stmt = $conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->execute([$groupId, $userId]);
}
if ($groupId) {
    sairDoGrupo($groupId, $userId, $conn);
    header('Location: grupos.php');
    exit;
}
?>