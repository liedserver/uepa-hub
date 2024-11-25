<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    exit('Usuário não está logado.');
}

$userId = $_SESSION['user_id'];

$query = $conn->prepare("SELECT id, nome FROM usuarios WHERE id != ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li data-user-id='{$row['id']}'>{$row['nome']}</li>";
    }
} else {
    echo "<li>Nenhum contato encontrado.</li>";
}
