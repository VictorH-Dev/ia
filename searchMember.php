<?php

$conexao = new mysqli('localhost', 'root', '', 'chat_app_db');
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

$stmt = $conexao->prepare("SELECT user_id, name FROM users WHERE name LIKE CONCAT('%', ?, '%')");

$stmt->bind_param("s", $searchTerm);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        echo "<button type='button' class='member' onclick='selectMember(" . $row['user_id'] . ", \"" . addslashes($row['name']) . "\")'>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</button>";
    }
} else {
    echo "Nenhum usuário encontrado.";
}

$stmt->close();
$conexao->close();
?>