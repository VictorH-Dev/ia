<?php
session_start();
include 'app/db.conn.php'; 

$userId = $_POST['userId'] ?? null;
$groupId = $_POST['groupId'] ?? null;

$response = ['success' => false, 'error_message' => ''];

if ($userId && $groupId) {

    $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
    if ($stmt->execute([$groupId, $userId])) {
        $response['success'] = true;
    } else {
        $response['error_message'] = 'Erro ao adicionar membro ao grupo.';
    }
} else {
    $response['error_message'] = 'Dados de usuário ou grupo não fornecidos.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>