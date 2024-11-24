<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: public/index.php");
    exit;
}
include 'includes/db.php';

// Obtém as informações do usuário
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT nome, bio, foto FROM usuarios WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result()->fetch_assoc();
$nome = htmlspecialchars($result['nome']);
$bio = htmlspecialchars($result['bio']);
$foto = htmlspecialchars($result['foto']);
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/edit_profile.css">
<div class="container">
    <h2>Editar Perfil</h2>
    <form action="process_edit_profile.php" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>
        <label for="bio">Biografia:</label>
        <textarea id="bio" name="bio" rows="4"><?php echo $bio; ?></textarea>
        <label for="foto">Foto de Perfil:</label>
        <input type="file" id="foto" name="foto" accept="image/*">
        <button type="submit">Salvar Alterações</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>

