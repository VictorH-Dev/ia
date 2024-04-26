<?php
if (isset($_POST['message']) || isset($_FILES['image'])) {
 
    $messageText = $_POST['message'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
  
    }


    echo "Mensagem e/ou imagem enviada com sucesso.";
} else {
    echo "Nenhuma mensagem ou imagem foi enviada.";
}

?>