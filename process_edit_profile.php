<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$nome = $_POST['nome'];
$bio = $_POST['bio'];
$foto = null;

// Atualiza a foto se for enviada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_name = uniqid() . '_' . $_FILES['foto']['name'];
    $foto_path = 'uploads/' . $foto_name;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
        $foto = $foto_name;
    } else {
        echo "Erro ao fazer upload da foto!";
        exit;
    }
}

// Atualiza as informações no banco
if ($foto) {
    $query = $conn->prepare("UPDATE usuarios SET nome = ?, bio = ?, foto = ? WHERE id = ?");
    $query->bind_param("sssi", $nome, $bio, $foto, $user_id);
} else {
    $query = $conn->prepare("UPDATE usuarios SET nome = ?, bio = ? WHERE id = ?");
    $query->bind_param("ssi", $nome, $bio, $user_id);
}

if ($query->execute()) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Erro ao atualizar perfil: " . $conn->error;
}

$conn->close();
?>
