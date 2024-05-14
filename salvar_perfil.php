<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    echo "Erro: Sessão não iniciada.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $biografia = $_POST["biografia"];
    $hobbies = $_POST["hobbies"];
    $profession = $_POST["profession"];
    $age = $_POST["age"];

    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "chat_app_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $user_id = $_SESSION["user_id"];
    $sql = "UPDATE users SET biography='$biografia', hobbies='$hobbies', profession='$profession', age=$age WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso!";
        header("Location: home.php?message=success"); 
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }

    $conn->close();
}
?>