<?php
include 'app/db.conn.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$memberId = $data['memberId'] ?? 0;
$groupId = $data['groupId'] ?? 0;

if (!empty($memberId) && !empty($groupId)) {
    $stmt = $conexao->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $groupId, $memberId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {

        echo json_encode(['success' => false, 'error_message' => $stmt->error]);
    }
} else {

    echo json_encode(['success' => false, 'error_message' => 'IDs de membro ou grupo não fornecidos.']);
}
?>