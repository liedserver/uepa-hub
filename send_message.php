<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['user_id']) || !isset($_POST['message'])) {
    exit('Erro na solicitação.');
}

$senderId = $_SESSION['user_id'];
$receiverId = $_POST['user_id'];
$message = trim($_POST['message']);

if (empty($message)) {
    exit('Mensagem vazia.');
}

$query = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
$query->bind_param("iis", $senderId, $receiverId, $message);

if ($query->execute()) {
    echo "success";
} else {
    echo "Erro ao enviar mensagem.";
}
