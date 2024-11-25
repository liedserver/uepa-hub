<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['user_id'])) {
    exit('Erro na solicitação.');
}

$userId = $_GET['user_id'];
$query = $conn->prepare("SELECT foto FROM usuarios WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$query->bind_result($foto);
$query->fetch();
$query->close();

$fotoPath = (empty($foto) || !file_exists("uploads/" . $foto)) ? 'uploads/default.png' : 'uploads/' . $foto;
echo htmlspecialchars($fotoPath);
?>
