<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT id, nome, senha, is_admin FROM usuarios WHERE matricula = ?");
    $query->bind_param("s", $matricula);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['is_admin'] = $user['is_admin']; // Armazena o status de administrador
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $query->close();
    $conn->close();
}
?>
