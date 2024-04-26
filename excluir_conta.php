<?php
session_start();
require 'app/db.conn.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $conn->beginTransaction();

    try {
        $stmtMessages = $conn->prepare("DELETE FROM chats WHERE from_id = :user_id OR to_id = :user_id");
        $stmtMessages->execute(['user_id' => $userId]);
        $stmtConversations = $conn->prepare("DELETE FROM conversations WHERE user_1 = :user_id OR user_2 = :user_id");
        $stmtConversations->execute(['user_id' => $userId]);
        $stmtUser = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmtUser->execute(['user_id' => $userId]);
        $conn->commit();
        session_destroy();
        header("Location: index.php");
        exit;
    } catch (Exception $e) {

        $conn->rollBack();
        echo "Erro ao excluir a conta: " . $e->getMessage();
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>