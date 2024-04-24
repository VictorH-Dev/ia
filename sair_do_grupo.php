<?php
session_start();
include 'app/db.conn.php';
include 'app/helpers/user.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$groupId = $_GET['group_id'] ?? null;

// Função para remover um membro do grupo
function sairDoGrupo($groupId, $userId, $conn) {
    $stmt = $conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->execute([$groupId, $userId]);
}

// Código para lidar com a requisição de sair do grupo
if ($groupId) {
    sairDoGrupo($groupId, $userId, $conn);
    header('Location: grupos.php');
    exit;
}
?>