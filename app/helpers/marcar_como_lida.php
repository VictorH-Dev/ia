<?php

include 'app/db.conn.php';

if (isset($_POST['conversationId'])) {
    $userId = $_SESSION['userId'];
    $conversationId = $_POST['conversationId']; 

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