<?php
// Inclua seu arquivo de conexão com o banco de dados aqui
include 'app/db.conn.php';

header('Content-Type: application/json');

// Recebe os dados enviados via AJAX
$data = json_decode(file_get_contents('php://input'), true);

$memberId = $data['memberId'] ?? 0;
$groupId = $data['groupId'] ?? 0;

// Verifica se os IDs do membro e do grupo foram fornecidos
if (!empty($memberId) && !empty($groupId)) {
    // Prepara a consulta SQL para adicionar o usuário ao grupo especificado
    $stmt = $conexao->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $groupId, $memberId);
    
    // Tenta executar a consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // Se houver um erro, retorna a mensagem de erro
        echo json_encode(['success' => false, 'error_message' => $stmt->error]);
    }
} else {
    // Retorna um erro se os IDs não foram fornecidos
    echo json_encode(['success' => false, 'error_message' => 'IDs de membro ou grupo não fornecidos.']);
}
?>