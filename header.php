<?php
// Código PHP para carregar os dados da sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: public/index.php");
    exit;
}

include 'includes/db.php';

// Consulta a imagem do usuário no banco de dados
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT foto FROM usuarios WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($foto);
$query->fetch();
$query->close();

$foto = (empty($foto) || !file_exists("uploads/" . $foto)) ? 'default.png' : $foto;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uepa Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="dashboard.php">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Brasaouepa.jpg/800px-Brasaouepa.jpg" alt="Uepa Hub" width="40" height="40" class="d-inline-block align-text-top">
                    Uepa Hub
                </a>

                <!-- Botão de menu mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Links de navegação -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="dashboard.php">Página Inicial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="calendar.php">Calendário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_event.php">Criar Evento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="messages.php">Mensagens</a>
                        </li>
                    </ul>

                    <!-- Dropdown do perfil -->
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="uploads/<?php echo htmlspecialchars($foto) . '?t=' . time(); ?>" alt="Foto de Perfil" class="rounded-circle" width="40" height="40">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="edit_profile.php">Editar Perfil</a></li>
                            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
