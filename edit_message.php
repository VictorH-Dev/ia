<?php
  	include 'app/db.conn.php';

if (isset($_POST['chat_id']) && isset($_POST['message'])) {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("UPDATE chats SET message = ? WHERE chat_id = ?");
    $stmt->bindParam(1, $message, PDO::PARAM_STR);
    $stmt->bindParam(2, $chat_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        error_log('Erro ao editar a mensagem: ' . print_r($stmt->errorInfo(), true));
        echo 'error';
    }
}
?>