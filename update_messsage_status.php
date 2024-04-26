<?php$query = "UPDATE conversations SET visualizada = TRUE WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_da_mensagem);

if ($stmt->execute()) {

    echo "Mensagem atualizada para visualizada.";
} else {

    echo "Erro ao atualizar a mensagem: " . $stmt->error;
}?