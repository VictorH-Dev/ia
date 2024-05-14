<?php 

function getConversation($user_id, $conn){
    // Ajuste a consulta SQL para incluir a tabela chats e ordenar pela coluna created_at
    $sql = "SELECT conversations.*, MAX(chats.created_at) as last_message_time
            FROM conversations
            LEFT JOIN chats ON (chats.from_id = conversations.user_1 OR chats.from_id = conversations.user_2)
            AND (chats.to_id = conversations.user_1 OR chats.to_id = conversations.user_2)
            WHERE conversations.user_1 = ? OR conversations.user_2 = ?
            GROUP BY conversations.conversation_id
            ORDER BY last_message_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $user_id]);

    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll();
        $user_data = [];
        
        foreach($conversations as $conversation){
            // Verifique qual usuário não é o usuário atual e obtenha seus dados
            $other_user_id = $conversation['user_1'] == $user_id ? $conversation['user_2'] : $conversation['user_1'];
            $sql2  = "SELECT * FROM users WHERE user_id=?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$other_user_id]);

            $allConversations = $stmt2->fetchAll();
            // Adicione a data da última mensagem aos dados do usuário
            $conversationData = $allConversations[0];
            $conversationData['last_message_time'] = $conversation['last_message_time'];
            array_push($user_data, $conversationData);
        }

        return $user_data;

    }else {
        $conversations = [];
        return $conversations;
    }  
}
?>