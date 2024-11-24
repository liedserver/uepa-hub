<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Uepa Hub</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
<div class="register-container">
    <h2>Registrar</h2>
    <form action="process_register.php" method="POST">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
        
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" placeholder="Escolha uma matrícula" required>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" placeholder="Escolha uma senha" required>
        
        <label for="confirm_password">Confirmar Senha:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme sua senha" required>
        
        <button type="submit">Registrar</button>
    </form>
    <p>Já possui uma conta? <a href="index.php">Faça login</a></p>
</div>
</body>
</html>

