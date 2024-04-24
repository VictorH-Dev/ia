<?php
// Conecte-se ao banco de dados
$conexao = new mysqli('localhost', 'root', '', 'chat_app_db');

// Verifique se a conexão foi bem-sucedida
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Verifique se um termo de pesquisa foi fornecido
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Prepare a consulta SQL para buscar usuários com base no termo de pesquisa
$stmt = $conexao->prepare("SELECT user_id, name FROM users WHERE name LIKE CONCAT('%', ?, '%')");

// Vincule o termo de pesquisa como parâmetro
$stmt->bind_param("s", $searchTerm);

// Execute a consulta
$stmt->execute();

// Obtenha os resultados
$result = $stmt->get_result();

// Verifique se há resultados
if ($result->num_rows > 0) {
    // Saída dos dados de cada linha
    while($row = $result->fetch_assoc()) {
        echo "<button type='button' class='member' onclick='selectMember(" . $row['user_id'] . ", \"" . addslashes($row['name']) . "\")'>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</button>";
    }
} else {
    echo "Nenhum usuário encontrado.";
}

// Feche a declaração e a conexão
$stmt->close();
$conexao->close();
?>