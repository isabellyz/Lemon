<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_email = $_SESSION['email'];

require_once 'conexao.php';

// Buscar dados do usuário
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Buscar posts do usuário
$query_posts = "SELECT p.*, u.nome_usuario 
                FROM posts p 
                JOIN usuarios u ON p.user_id = u.id 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC";
$stmt_posts = mysqli_prepare($conn, $query_posts);
mysqli_stmt_bind_param($stmt_posts, "i", $usuario_id);
mysqli_stmt_execute($stmt_posts);
$result_posts = mysqli_stmt_get_result($stmt_posts);
$posts_usuario = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_posts);

// Contar total de posts do usuário
$total_posts = count($posts_usuario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <title>Perfil de <?= htmlspecialchars($usuario_nome) ?> - Lemon</title>
</head>

<body style="background-color: #FCFADB;">
    <header class="container-fluid py-3" style="background-color: #289256;">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="home.php"> <img src="img/logo.png" alt="Logo" width="100px"> </a>
            </div>
            <div class="col-6 text-center">
                <h1 style="color: white;" class="m-0">Meu Perfil</h1>
            </div>
            <div class="col-3 text-end p-4">
                <a href="categorias/pg.php" class="btn btn-outline-light btn-sm">Voltar ao Feed</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="p-3 text-center" style="border: 2px solid #289256;">
                    <h5>Menu</h5>
                    <a href="home.php" class="d-block py-2">Página Inicial</a>
                    <a href="edit_profile.php" class="d-block py-2">Editar Perfil</a>
                    <a href="logout.php" class="d-block py-2 text-danger">Sair</a>
                </div>
            </div>

            <!-- coluna do meio -->
            <div class="col-12 col-md-6">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" 
                             alt="avatar" class="rounded-circle mb-3" width="120" height="120">
                        <h3><?= htmlspecialchars($usuario_nome) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($usuario_email) ?></p>
                        
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <span class="badge bg-success">Administrador</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-4 text-center">
                        <div class="p-3" style="border: 1px solid #289256; border-radius: 10px;">
                            <h5><?= $total_posts ?></h5>
                            <small>Posts</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="p-3" style="border: 1px solid #289256; border-radius: 10px;">
                            <h5>150</h5>
                            <small>Seguidores</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="p-3" style="border: 1px solid #289256; border-radius: 10px;">
                            <h5>89</h5>
                            <small>Seguindo</small>
                        </div>
                    </div>
                </div>

                <h4>Meus Posts (<?= $total_posts ?>)</h4>
                
                <?php if (empty($posts_usuario)): ?>
                    <div class="text-center p-4" style="border: 2px solid #289256; border-radius: 10px;">
                        <p class="text-muted">Você ainda não fez nenhum post.</p>
                        <a href="pg.php" class="btn btn-success">Fazer meu primeiro post</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts_usuario as $post): ?>
                        <div class="mypost mb-3" style="border: 2px solid #289256; padding: 20px; border-radius: 10px;">
                            <div class="d-flex align-items-center mb-2">
                                <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" 
                                     alt="avatar" class="rounded-circle" width="40" height="40">
                                <div class="ms-2">
                                    <h6 class="mb-0"><?= htmlspecialchars($post['nome_usuario']) ?></h6>
                                    <small class="text-muted">
                                        <?php 
                                        // Formata a data sem usar strtotime() para evitar warning
                                        $data = $post['created_at'];
                                        $data_formatada = substr($data, 8, 2) . '/' . substr($data, 5, 2) . '/' . substr($data, 0, 4);
                                        $hora = substr($data, 11, 5);
                                        echo $data_formatada . ' ' . $hora;
                                        ?>
                                        • <?= htmlspecialchars($post['category']) ?>
                                    </small>
                                </div>
                            </div>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Coluna lateral direita -->
            <div class="col-12 col-md-3">
                <div class="p-3 text-center" style="border: 2px solid #289256;">
                    <h5>Ações Rápidas</h5>
                    <a href="edit_profile.php" class="btn btn-success w-100 mb-2">Editar Perfil</a>
                    <a href="change_password.php" class="btn btn-outline-success w-100 mb-2">Alterar Senha</a>
                    
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <a href="admin.php" class="btn btn-primary w-100 mb-2">Painel Admin</a>
                    <?php endif; ?>
                </div>

                <!-- Estatísticas -->
                <div class="p-3 text-center mt-3" style="border: 2px solid #289256;">
                    <h5>Estatísticas</h5>
                    <p><strong>Total de Posts:</strong> <?= $total_posts ?></p>
                    <p><strong>Primeiro Post:</strong> 
                        <?php if (!empty($posts_usuario)): ?>
                            <?php 
                            $primeiro_post = end($posts_usuario);
                            $data_primeiro = $primeiro_post['created_at'];
                            echo substr($data_primeiro, 8, 2) . '/' . substr($data_primeiro, 5, 2) . '/' . substr($data_primeiro, 0, 4);
                            ?>
                        <?php else: ?>
                            Nenhum post
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>