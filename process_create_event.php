<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Verificar se o usuário é um administrador
$is_admin = $_SESSION['is_admin'] ?? false;
if (!$is_admin) {
    echo "Acesso negado. Apenas administradores podem criar eventos.";
    exit;
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_evento = $_POST['data_evento'];

    $query = $conn->prepare("INSERT INTO eventos (titulo, descricao, data_evento) VALUES (?, ?, ?)");
    $query->bind_param("sss", $titulo, $descricao, $data_evento);

    if ($query->execute()) {
        echo "Evento criado com sucesso! <a href='calendar.php'>Voltar para o Calendário</a>";
    } else {
        echo "Erro ao criar evento: " . $conn->error;
    }

    $query->close();
    $conn->close();
}
?>
