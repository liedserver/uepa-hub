<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "As senhas não coincidem.";
        exit;
    }

    $query = $conn->prepare("SELECT id FROM matriculas_validas WHERE matricula = ?");
    $query->bind_param("s", $matricula);
    $query->execute();
    $query->store_result();

    if ($query->num_rows === 0) {
        echo "Matrícula inválida.";
        exit;
    }

    $query->close();

    $query = $conn->prepare("SELECT id FROM usuarios WHERE matricula = ?");
    $query->bind_param("s", $matricula);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        echo "Matrícula já cadastrada.";
        exit;
    }

    $query->close();

    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    $query = $conn->prepare("INSERT INTO usuarios (nome, matricula, senha) VALUES (?, ?, ?)");
    $query->bind_param("sss", $nome, $matricula, $password_hashed);

    if ($query->execute()) {
        echo "Registro concluído com sucesso! <a href='index.php'>Faça login</a>";
    } else {
        echo "Erro ao registrar: " . $conn->error;
    }

    $query->close();
    $conn->close();
}
?>
