<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redireciona para o login
    exit;
}

include 'includes/db.php';
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/dashboard.css">

<div class="dashboard">
    <main class="feed">
        <!-- Formulário de Criação de Post -->
        <div class="post-form">
            <form action="process_post.php" method="POST" enctype="multipart/form-data">
                <textarea name="text" placeholder="Escreva algo..." maxlength="255" required></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit">Publicar</button>
            </form>
        </div>

        <!-- Exibição dos Posts -->
        <?php
        $query = $conn->query("SELECT posts.*, usuarios.nome, usuarios.foto FROM posts 
                               JOIN usuarios ON posts.user_id = usuarios.id 
                               ORDER BY posts.created_at DESC");

        while ($post = $query->fetch_assoc()) {
            $isAdmin = $_SESSION['is_admin'] ?? false;
            $isAuthor = $post['user_id'] == $_SESSION['user_id'];
            echo '<div class="post">';

            // Cabeçalho do Post
            echo '<div class="post-header">';
            echo '<img src="uploads/' . htmlspecialchars($post['foto']) . '" alt="Foto de Perfil" class="post-profile-pic">';
            echo '<div class="post-info">';
            echo '<h3>' . htmlspecialchars($post['nome']) . '</h3>';
            echo '</div>';
            echo '<span class="post-dots" onclick="toggleDropdown(this)">⋮</span>';
            echo '<div class="dropdown-menu">';
            if ($isAuthor || $isAdmin) {
                echo '<button class="dropdown-item delete-post" data-post-id="' . $post['id'] . '">Excluir</button>';
            }
            echo '<button class="dropdown-item report-post">Denunciar</button>';
            echo '</div>';
            echo '</div>'; // Fim do post-header

            // Conteúdo do Post
            echo '<p>' . htmlspecialchars($post['text']) . '</p>';
            if ($post['image']) {
                echo '<img src="uploads/' . htmlspecialchars($post['image']) . '" alt="Imagem do Post" class="post-image">';
            }
            echo '<small>Publicado em: ' . $post['created_at'] . '</small>';

            // Ações no Post (Curtidas e Comentários)
            echo '<div class="post-actions">';
            
            // Curtidas
            $userLiked = userLiked($conn, $post['id'], $_SESSION['user_id']);
            echo '<div class="like-section">';
            echo '<button class="like-btn ' . ($userLiked ? 'liked' : '') . '" data-post-id="' . $post['id'] . '">';
            echo '<span class="like-icon">👍</span>';
            echo '</button>';
            echo '<span class="like-count" id="like-count-' . $post['id'] . '">';
            $likes_query = $conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = ?");
            $likes_query->bind_param("i", $post['id']);
            $likes_query->execute();
            $likes_result = $likes_query->get_result()->fetch_assoc();
            echo $likes_result['total_likes'];
            echo '</span>';
            echo '</div>';

            // Comentários
            echo '<form class="comment-form" data-post-id="' . $post['id'] . '">';
            echo '<textarea name="comment" placeholder="Adicione um comentário..." required></textarea>';
            echo '<button type="submit">Comentar</button>';
            echo '</form>';
            echo '<div class="comments" id="comments-' . $post['id'] . '">';

            $comments_query = $conn->prepare("
            SELECT comments.comment, comments.created_at, usuarios.nome, usuarios.foto
            FROM comments
            JOIN usuarios ON comments.user_id = usuarios.id
            WHERE comments.post_id = ?
            ORDER BY comments.created_at DESC
        ");
        $comments_query->bind_param("i", $post['id']);
        $comments_query->execute();
        $comments_result = $comments_query->get_result();
        
        while ($comment = $comments_result->fetch_assoc()) {
            echo '<div class="comment">';
            echo '<img src="uploads/' . htmlspecialchars($comment['foto']) . '" alt="Foto de Perfil" class="comment-profile-pic">';
            echo '<strong>' . htmlspecialchars($comment['nome']) . ':</strong>'; // Mostrando o nome do usuário
            echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
            echo '</div>';
        }

            echo '</div>'; // Fim dos Comentários
            echo '</div>'; // Fim das Ações no Post
            echo '</div>'; // Fim do Post
        }

        $query->close();

        function userLiked($conn, $postId, $userId) {
            $query = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
            $query->bind_param("ii", $userId, $postId);
            $query->execute();
            return $query->get_result()->num_rows > 0;
        }
        ?>
    </main>
</div>

<!-- Modal de confirmação -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h3>Confirmar Exclusão</h3>
        <p>Tem certeza de que deseja excluir este post?</p>
        <div class="modal-actions">
            <button id="confirmDelete" class="btn-delete">Excluir</button>
            <!--<button class="btn-cancel modal-close">Cancelar</button>-->
        </div>
    </div>
</div>



<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Função para exibir ou esconder o menu dropdown
function toggleDropdown(element) {
    const dropdown = element.nextElementSibling;
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== dropdown) {
            menu.style.display = 'none';
        }
    });
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Fechar dropdown ao clicar fora
document.addEventListener('click', function(event) {
    if (!event.target.matches('.post-dots') && !event.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});

let postToDelete = null; // Variável para armazenar o ID do post a ser excluído

// Mostrar o modal de confirmação
$(document).on('click', '.delete-post', function () {
    postToDelete = $(this).data('post-id'); // Armazena o ID do post
    $('#deleteModal').fadeIn(); // Exibe o modal
});

// Fechar o modal ao clicar em "Cancelar" ou no botão de fechar
$(document).on('click', '.modal-close', function () {
    $('#deleteModal').fadeOut(); // Oculta o modal
});

// Confirmar a exclusão do post
$('#confirmDelete').on('click', function () {
    if (postToDelete) {
        $.ajax({
            url: 'delete_post.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ post_id: postToDelete }),
            success: function (response) {
                if (response.trim() === 'success') {
                    location.reload(); // Recarrega a página após a exclusão
                } else if (response.trim() === 'unauthorized') {
                    alert('Você não tem permissão para excluir este post.');
                } else {
                    alert('Ocorreu um erro ao tentar excluir o post.');
                }
            },
            error: function () {
                alert('Erro ao processar a solicitação.');
            }
        });
        $('#deleteModal').fadeOut(); // Fecha o modal após enviar a requisição
    }
});


// AJAX para Curtidas
$(document).on('click', '.like-btn', function () {
    const button = $(this);
    const postId = button.data('post-id');
    const likeCountElement = $('#like-count-' + postId);

    // Envia a requisição de curtida/descurtida
    $.ajax({
        url: 'process_like.php',
        type: 'POST',
        data: { post_id: postId },
        success: function (response) {
            if (response.trim() === 'liked') {
                button.addClass('liked'); // Adiciona a classe 'liked' para alterar o estilo
            } else if (response.trim() === 'unliked') {
                button.removeClass('liked'); // Remove a classe 'liked' para voltar ao estilo padrão
            }

            // Atualiza o contador de curtidas
            $.ajax({
                url: 'fetch_likes.php',
                type: 'POST',
                data: { post_id: postId },
                success: function (likeCount) {
                    likeCountElement.text(likeCount);
                }
            });
        },
        error: function () {
            alert('Erro ao processar a ação de curtir.');
        }
    });
});


// AJAX para adicionar comentários
$(document).on('submit', '.comment-form', function (e) {
    e.preventDefault();

    const postId = $(this).data('post-id');
    const commentText = $(this).find('textarea[name="comment"]').val();
    const commentsSection = $('#comments-' + postId);

    if (commentText.trim() === '') {
        alert('O comentário não pode estar vazio!');
        return;
    }

    $.ajax({
        url: 'process_comment.php',
        type: 'POST',
        data: { post_id: postId, comment: commentText },
        success: function (response) {
            if (response.trim() === 'success') {
                // Carrega novamente os comentários
                $.ajax({
                    url: 'fetch_comments.php',
                    type: 'POST',
                    data: { post_id: postId },
                    success: function (commentsHTML) {
                        commentsSection.html(commentsHTML);
                    }
                });
                // Limpa o campo de comentário
                $(this).find('textarea[name="comment"]').val('');
            } else {
                alert('Erro ao adicionar o comentário. Tente novamente.');
            }
        },
        error: function () {
            alert('Erro ao processar a solicitação.');
        }
    });
});

</script>
