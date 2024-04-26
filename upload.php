<?php

session_start();
include 'app/db.conn.php';

if(isset($_POST["submit"])) {
  $chat_id = isset($_POST['chat_id']) ? $_POST['chat_id'] : null;
  if ($chat_id === null) {
    die('O chat_id não foi fornecido.');
  }
  
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $nome_arquivo = basename($_FILES["fileToUpload"]["name"]);
  $tipo_arquivo = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $tamanho_arquivo = $_FILES["fileToUpload"]["size"];
  $data_envio = date('Y-m-d H:i:s');

  $conexao->begin_transaction();
  
  $stmt = null; 
  try {
   
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
   
      $stmt = $conexao->prepare("INSERT INTO arquivos (chat_id, nome_arquivo, caminho_arquivo, tipo_arquivo, tamanho_arquivo, data_envio) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssii", $chat_id, $nome_arquivo, $target_file, $tipo_arquivo, $tamanho_arquivo, $data_envio);
      $stmt->execute();
      
      echo "O arquivo ". htmlspecialchars( $nome_arquivo ). " foi enviado.";
      

      $conexao->commit();
    } else {
      throw new Exception("Desculpe, houve um erro ao enviar seu arquivo.");
    }
  } catch (Exception $exception) {

    $conexao->rollback();
    echo $exception->getMessage();
  } finally {
  
    if ($stmt instanceof mysqli_stmt) {
      $stmt->close();
    }
  }
}
?>