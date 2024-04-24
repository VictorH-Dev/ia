<?php
session_start();
include 'app/db.conn.php';

// Verifica se uma nova imagem foi enviada
if(isset($_FILES["fotoPerfil"])) {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

    $target_dir = "uploads/perfis/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $fotoPerfil = $_FILES["fotoPerfil"];
    $novo_nome_arquivo = uniqid() . '.' . strtolower(pathinfo($fotoPerfil["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $novo_nome_arquivo;

    // Verifica se a imagem atual não é a padrão antes de tentar excluí-la
    $stmt = $conn->prepare("SELECT p_p FROM users WHERE user_id = ?");
    $stmt->bindValue(1, $user_id);
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user && $user['p_p'] !== 'user-default.png') {
        // Exclui a imagem de perfil anterior
        unlink($target_dir . $user['p_p']);
    }

    $errors = [];
    if($fotoPerfil["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Erro no upload: " . $fotoPerfil["error"];
    }

    $check = getimagesize($fotoPerfil["tmp_name"]);
    if($check === false) {
        $errors[] = "O arquivo não é uma imagem.";
    }

    if ($fotoPerfil["size"] > 500000) {
        $errors[] = "Desculpe, seu arquivo é muito grande.";
    }

    if(!in_array($check['mime'], ['image/jpg', 'image/jpeg', 'image/png'])) {
        $errors[] = "Desculpe, apenas arquivos JPG, JPEG e PNG são permitidos.";
    }

    if(empty($errors) && move_uploaded_file($fotoPerfil["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("UPDATE users SET p_p = ? WHERE user_id = ?");
        $stmt->bindValue(1, $novo_nome_arquivo);
        $stmt->bindValue(2, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Foto de perfil atualizada com sucesso!'); window.location.href='home.php';</script>";
        } else {
            $errors[] = "Erro ao atualizar a foto de perfil: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Adiciona erro de falha no upload
        $errors[] = "Falha ao mover o arquivo para o diretório de destino.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}
?>