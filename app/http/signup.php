<?php
session_start();
include '../db.conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    $data = 'name=' . $name . '&username=' . $username;

    if (empty($name)) {
        $em = "Necessário ter nome";
        header("Location: ../../signup.php?error=$em");
        exit;
    } elseif (empty($username)) {
        $em = "Necessário ter usuário";
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    } elseif (empty($password)) {
        $em = "Necessário ter senha";
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    } else {
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $em = "O usuário ($username) já existe";
            header("Location: ../../signup.php?error=$em&$data");
            exit;
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            if (isset($_FILES['pp'])) {
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);
                    $allowed_exs = array("jpg", "jpeg", "png");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $new_img_name = $username . '.' . $img_ex_lc;
                        $img_upload_path = '../../uploads/perfis/' . $new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        $em = "Você não pode fazer upload de arquivos deste tipo";
                        header("Location: ../../signup.php?error=$em&$data");
                        exit;
                    }
                }
            }

            if (isset($new_img_name)) {
                $sql = "INSERT INTO users (name, username, password, p_p) VALUES (?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password, $new_img_name]);
            } else {
                $sql = "INSERT INTO users (name, username, password) VALUES (?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password]);
            }

            $sm = "Conta criada com sucesso!";
            header("Location: ../../index.php?success=$sm");
            exit;
        }
    }
} else {
    header("Location: ../../signup.php");
    exit;
}
?>