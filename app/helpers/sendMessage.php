<?php
// Verifique se a mensagem de texto ou a imagem foi enviada
if (isset($_POST['message']) || isset($_FILES['image'])) {
    // Processar a mensagem de texto
    $messageText = $_POST['message'] ?? '';

    // Processar o upload da imagem
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Código para processar o upload da imagem
        // ...
    }

    // Código para salvar a mensagem de texto e/ou o caminho da imagem no banco de dados
    // ...

    echo "Mensagem e/ou imagem enviada com sucesso.";
} else {
    echo "Nenhuma mensagem ou imagem foi enviada.";
}
?>