<?php

$mysqli = new mysqli("localhost", "root", "", "chat_app_db");

if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}


$userId = 1; 
$query = "SELECT c.conversation_id, u.name, COUNT(*) as unread_count
          FROM chats AS ch
          JOIN conversations AS c ON ch.chat_id = c.conversation_id
          JOIN users AS u ON u.user_id = IF(c.user_1 = $userId, c.user_2, c.user_1)
          WHERE (ch.to_id = $userId AND ch.opened = 0)
          GROUP BY c.conversation_id";

$result = $mysqli->query($query);

if ($result) {

    while ($row = $result->fetch_assoc()) {
        echo "Conversa com " . $row['name'] . " tem " . $row['unread_count'] . " mensagens não lidas.<br>";
    }
} else {
    echo "Erro ao executar a consulta: " . $mysqli->error;
}

// Feche a conexão
$mysqli->close();
?>