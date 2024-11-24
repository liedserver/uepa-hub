<?php
session_start();
include 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$text = $_POST['text'];
$image = null;

// Processa o upload da imagem
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_name = uniqid() . '_' . $_FILES['image']['name'];
    $image_path = 'uploads/' . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        $image = $image_name;
    } else {
        echo "Erro ao fazer upload da imagem!";
        exit;
    }
}

// Insere o post no banco de dados
$query = $conn->prepare("INSERT INTO posts (user_id, text, image) VALUES (?, ?, ?)");
$query->bind_param("iss", $user_id, $text, $image);

if ($query->execute()) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Erro ao publicar o post: " . $conn->error;
}

$query->close();
$conn->close();
?>
