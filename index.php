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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="container login-container">
    <div class="row w-100">
        <!-- Seção da imagem -->
        <div class="col-lg-6 app-preview d-none d-lg-flex justify-content-center align-items-center">
            <img src="assets/img/brasaouepa1.png" alt="Preview do App">
        </div>
        <!-- Formulário de Login -->
        <div class="col-lg-6 d-flex justify-content-center align-items-center">
            <form action="process_login.php" method="POST" class="login-form w-100" style="max-width: 400px;">
                <h2>Login</h2>
                <div class="mb-3">
                    <label for="matricula" class="form-label">Matrícula:</label>
                    <input type="text" id="matricula" name="matricula" class="form-control" placeholder="Digite sua matrícula" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
                <div class="form-text">
                    Não tem uma conta? <a href="register.php" class="text-decoration-none">Registre-se</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
