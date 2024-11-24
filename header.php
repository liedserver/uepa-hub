<?php
// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redireciona o usuário para a página de login caso não esteja logado
if (!isset($_SESSION['user_id'])) {
    header("Location: public/index.php");
    exit;
}

// Inclui a conexão com o banco de dados
include 'includes/db.php';

// Consulta a imagem do usuário no banco de dados
$userId = $_SESSION['user_id'];
$query = $conn->prepare("SELECT foto FROM usuarios WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
$userData = $result->fetch_assoc();

// Verifica se o usuário possui uma foto definida ou utiliza a padrão
$foto = (!empty($userData['foto']) && file_exists("uploads/" . $userData['foto']))
    ? $userData['foto']
    : 'default.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uepa Hub</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <!-- Logo -->
            <div class="logo">
                <h1>Uepa Hub</h1>
            </div>

            <!-- Links do menu -->
            <a href="dashboard.php">Página Inicial</a>
            <a href="calendar.php">Calendário</a>
            <a href="create_event.php">Criar Evento</a>
            <a href="messages.php">Mensagens</a>

            <!-- Foto de Perfil -->
            <div class="profile-dropdown">
                <img src="uploads/<?php echo htmlspecialchars($foto); ?>?<?php echo time(); ?>" 
                     alt="Foto de Perfil" 
                     class="profile-pic" 
                     id="profile-pic">
                
                <!-- Menu dropdown -->
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="edit_profile.php">Editar Perfil</a>
                    <a href="logout.php">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // Mostra ou esconde o menu dropdown ao clicar na foto de perfil
        $(document).ready(function () {
            $('#profile-pic').on('click', function (e) {
                e.stopPropagation(); // Previne o fechamento do menu no clique
                $('#dropdown-menu').toggleClass('show');
            });

            // Fecha o menu dropdown ao clicar fora dele
            $(document).on('click', function () {
                $('#dropdown-menu').removeClass('show');
            });
        });
    </script>
</body>
</html>
