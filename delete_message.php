<?php
  	include 'app/db.conn.php';
session_start(); 

if (isset($_POST['chat_id'])) {
    $chat_id = $_POST['chat_id'];
    $user_id = $_SESSION['user_id']; 
    $stmt = $conn->prepare("SELECT * FROM chats WHERE chat_id = ? AND from_id = ?");
    $stmt->bindParam(1, $chat_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("DELETE FROM chats WHERE chat_id = ?");
        $stmt->bindParam(1, $chat_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            error_log('Erro ao excluir a mensagem: ' . print_r($stmt->errorInfo(), true));
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
?>