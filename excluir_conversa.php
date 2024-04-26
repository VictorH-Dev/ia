<?php
session_start();
require 'app/db.conn.php';

if (isset($_SESSION['user_id']) && isset($_GET['conversation_id'])) {
    $userId = $_SESSION['user_id'];
    $conversationId = $_GET['conversation_id'];

    // Excluir todas as mensagens da conversa
    $stmtMessages = $conn->prepare("DELETE FROM chats WHERE conversation_id = :conversation_id");
    $stmtMessages->execute(['conversation_id' => $conversationId]);

    // Excluir a conversa
    $stmtConversation = $conn->prepare("DELETE FROM conversations WHERE conversation_id = :conversation_id");
    $stmtConversation->execute(['conversation_id' => $conversationId]);

    // Redirecionar de volta à página inicial
    header("Location: home.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>