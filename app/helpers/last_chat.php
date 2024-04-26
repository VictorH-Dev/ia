<?php

function lastChat($id_1, $id_2, $conn){
   
   $sql = "SELECT * FROM chats
           WHERE (from_id=? AND to_id=?)
           OR    (to_id=? AND from_id=?)
           ORDER BY chat_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_1, $id_2, $id_1, $id_2]);

    if ($stmt->rowCount() > 0) {
        $chat = $stmt->fetch();
        $message = $chat['message'];
        $limit = 50; // Set the character limit for the message

        // Truncate the message if it exceeds the limit
        if (strlen($message) > $limit) {
            return substr($message, 0, $limit) . '...';
        } else {
            return $message;
        }
    } else {
        $chat = '';
        return $chat;
    }
}
?>