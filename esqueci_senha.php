<?php
// Inicie a sessão e inclua o arquivo de conexão com o banco de dados
session_start();
include 'app/db.conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Verifique se o nome de usuário existe no banco de dados
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Atualize a senha do usuário no banco de dados
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $stmt->execute([$hashed_password, $username]);

        // Defina a mensagem de sucesso na sessão
        $_SESSION['success'] = "Sua senha foi redefinida com sucesso.";

        // Redirecione para index.php
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error'] = "Nome de usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <style>
        /* reset_senha.css */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    width: 400px;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box; /* Add this line to include padding in width */
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #0d6efd;
    color: white;
    cursor: pointer;
}

button:hover {
    background-color: #4cae4c;
}

.alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: #fff;
}

.alert-danger {
    background-color: #d9534f;
}

.alert-success {
    background-color: #0d6efd;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Redefinir Senha</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
            </div>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
            </div>
        <?php unset($_SESSION['success']); endif; ?>

        <form action="esqueci_senha.php" method="post">
            <label for="username">Nome de usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="new_password">Nova Senha:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Redefinir Senha</button>
        </form>
    </div>
</body>
</html>