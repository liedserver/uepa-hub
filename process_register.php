<?php
include 'includes/db.php';

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica se as senhas coincidem
    if ($password !== $confirm_password) {
        die("As senhas não coincidem. <a href='register.php'>Voltar</a>");
    }

    // Verifica se a matrícula está na tabela de matrículas válidas
    $query = $conn->prepare("SELECT id FROM matriculas_validas WHERE matricula = ?");
    $query->bind_param("s", $matricula);
    $query->execute();
    $query->store_result();

    if ($query->num_rows === 0) {
        die("Matrícula inválida. <a href='register.php'>Voltar</a>");
    }

    $query->close();

    // Verifica se a matrícula já está cadastrada como usuário
    $query = $conn->prepare("SELECT id FROM usuarios WHERE matricula = ?");
    $query->bind_param("s", $matricula);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        die("Matrícula já cadastrada. <a href='register.php'>Voltar</a>");
    }

    $query->close();

    // Hash da senha
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Insere no banco de dados
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
