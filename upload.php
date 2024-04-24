<?php
// Conexão com o banco de dados
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
  
  // Inicia a transação
  $conexao->begin_transaction();
  
  $stmt = null; // Inicialize a variável $stmt
  try {
    // Tente fazer o upload do arquivo
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      // Prepara a declaração para inserir as informações do arquivo no banco de dados
      $stmt = $conexao->prepare("INSERT INTO arquivos (chat_id, nome_arquivo, caminho_arquivo, tipo_arquivo, tamanho_arquivo, data_envio) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssii", $chat_id, $nome_arquivo, $target_file, $tipo_arquivo, $tamanho_arquivo, $data_envio);
      $stmt->execute();
      
      echo "O arquivo ". htmlspecialchars( $nome_arquivo ). " foi enviado.";
      
      // Se tudo estiver ok, confirma a transação
      $conexao->commit();
    } else {
      throw new Exception("Desculpe, houve um erro ao enviar seu arquivo.");
    }
  } catch (Exception $exception) {
    // Se houver algum erro, desfaz a transação
    $conexao->rollback();
    echo $exception->getMessage();
  } finally {
    // Verifica se $stmt é um objeto antes de chamar close()
    if ($stmt instanceof mysqli_stmt) {
      $stmt->close();
    }
  }
}
?>