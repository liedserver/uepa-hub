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
    <title>Login - Uepa Hub</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form action="process_login.php" method="POST">
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" placeholder="Digite sua matrícula" required>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Registre-se</a></p>
</div>
</body>
</html>

