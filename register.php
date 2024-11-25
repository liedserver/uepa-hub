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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
<div class="register-container">
    <div class="register-form">
        <h2>Uepa Hub - Registrar</h2>
        <form action="process_register.php" method="POST">
            <input type="text" id="nome" name="nome" placeholder="Nome Completo" required>
            <input type="text" id="matricula" name="matricula" placeholder="Matrícula" required>
            <input type="password" id="password" name="password" placeholder="Senha" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Senha" required>
            <button type="submit">Registrar</button>
        </form>
        <p>Já possui uma conta? <a href="index.php">Faça login</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
