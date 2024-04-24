<?php 

session_start();

# Verifique se o usuário está logado
if (isset($_SESSION['username'])) {

    if (isset($_POST['id_2'])) {
    
        # Arquivo de conexão com o banco de dados
        include '../db.conn.php';

        $id_1  = $_SESSION['user_id'];
        $id_2  = $_POST['id_2'];

        $sql = "SELECT * FROM chats
                WHERE to_id=?
                AND   from_id= ?
                ORDER BY chat_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_1, $id_2]);

        if ($stmt->rowCount() > 0) {
            $chats = $stmt->fetchAll();

            # Percorrendo as mensagens do chat
            foreach ($chats as $chat) {
                if ($chat['opened'] == 0) {
                    
                    $opened = 1;
                    $chat_id = $chat['chat_id'];

                    $sql2 = "UPDATE chats
                             SET opened = ?
                             WHERE chat_id = ?";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->execute([$opened, $chat_id]); 

                    # Exibição da mensagem
                    ?>
                      <p class="ltext border rounded p-2 mb-1">
                        <?=$chat['message']?> 
                        <small class="d-block">
                            <?=$chat['created_at']?>
                        </small>      
                      </p>        
                    <?php

                    # Aqui você deve adicionar a lógica para buscar e exibir arquivos
                    # Suponha que você tenha uma tabela 'arquivos' onde os arquivos são armazenados
                    $sql3 = "SELECT * FROM arquivos WHERE chat_id = ?";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->execute([$chat_id]);

                    if ($stmt3->rowCount() > 0) {
                        $arquivos = $stmt3->fetchAll();
                        foreach ($arquivos as $arquivo) {
                            # Exibição do link do arquivo
                            echo "<p><a href='uploads/" . $arquivo['nome_arquivo'] . "' download>" . $arquivo['nome_arquivo'] . "</a></p>";
                        }
                    }
                }
            }
        }

    }

}else {
    header("Location: ../../index.php");
    exit;
}
?>