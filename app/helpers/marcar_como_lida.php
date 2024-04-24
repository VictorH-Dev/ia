<?php
// marcarComoLida.php
// Conexão com o banco de dados
include 'app/db.conn.php';

// Verifique se o ID da conversa foi passado
if (isset($_POST['conversationId'])) {
    $userId = $_SESSION['userId']; // Substitua pela sessão do usuário logado
    $conversationId = $_POST['conversationId']; // ID da conversa passado pelo POST

    // Consulta para atualizar o status da mensagem para 'visualizada'
    $query = "UPDATE chats SET opened = 1 WHERE to_id = $userId AND chat_id = $conversationId";

    if ($mysqli->query($query) === TRUE) {
        echo "Mensagens atualizadas como visualizadas.";
    } else {
        echo "Erro ao atualizar mensagens: " . $mysqli->error;
    }

    // Feche a conexão
    $mysqli->close();
} else {
    echo "ID da conversa não fornecido.";
}
?>