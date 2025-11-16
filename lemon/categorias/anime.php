<?php
session_start();
require_once '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$categoria = 'Animes e Mangás';

// Processar nova postagem
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conteudo'])) {
    $conteudo = trim($_POST['conteudo']);
    
    if (!empty($conteudo)) {
        $query = "INSERT INTO posts (user_id, content, category, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $conteudo, $categoria);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Recarregar a página para mostrar a nova postagem
        header("Location: anime.php");
        exit();
    }
}

// Buscar postagens desta categoria
$query_posts = "SELECT p.*, u.nome_usuario 
                FROM posts p 
                JOIN usuarios u ON p.user_id = u.id 
                WHERE p.category = ? 
                ORDER BY p.created_at DESC";
$stmt_posts = mysqli_prepare($conn, $query_posts);
mysqli_stmt_bind_param($stmt_posts, "s", $categoria);
mysqli_stmt_execute($stmt_posts);
$result_posts = mysqli_stmt_get_result($stmt_posts);
$posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_posts);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <title>Lemon - Anime e Mangá</title>
</head>

<body style="background-color: #FCFADB;">
    <header class="container-fluid py-3" style="background-color: #289256;">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="../home.php"> <img src="../img/logo.png" alt="Logo" width="100px"> </a>
            </div>
            <div class="col-6 text-center">
                <h1 style="color: white; text-transform: uppercase;" class="m-0">Anime e Mangá</h1>
            </div>
            <div class="col-3 text-end">
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            <!-- primeira coluna -->
            <div class="col-12 col-md-3">
                <div class="p-3 text-center" style="border: 2px solid #289256;">
                    <h5 class="m-2"> Categorias</h5>
                    <p>Comida e Bebida</p>
                    <p>Moda e Estilo</p>
                    <p>Esportes</p>
                    <p>Pets e Animais</p>
                    <p>Livros e Literatura</p>
                    <p>Arte e Design</p>
                    <p>Cultura Mundial</p>
                    <p>Curiosidades</p>
                </div>
            </div>

            <!-- coluna do meio -->
            <div class="col-12 col-md-6">
                <!-- formulário de postagem -->
                <div class="post-box mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" alt="avatar" class="rounded-circle" width="40" height="40">
                        <h5 class="ms-2"><?= htmlspecialchars($usuario_nome) ?></h5>
                    </div>

                    <form method="post" action="">
                        <textarea name="conteudo" class="input-post" placeholder="O que está acontecendo na categoria Animes e Mangás?" maxlength="280" required></textarea>
                        <button type="submit" class="post-button m-2">Postar</button>
                    </form>
                </div>

                <!-- posts dos usuários -->
                <div id="postsContainer">
                    <!-- Posts reais do banco de dados -->
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" alt="avatar" class="rounded-circle" width="40" height="40">
                                    <div class="ms-2">
                                        <h5 class="mb-0"><?= htmlspecialchars($post['nome_usuario']) ?></h5>
                                        <small class="text-muted">
                                            <?php 
                                            // Formata a data sem usar strtotime() para evitar warning
                                            $data = $post['created_at'];
                                            $data_formatada = substr($data, 8, 2) . '/' . substr($data, 5, 2) . '/' . substr($data, 0, 4);
                                            $hora = substr($data, 11, 5);
                                            echo $data_formatada . ' ' . $hora;
                                            ?>
                                        </small>
                                    </div>
                                </div>
                                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Posts fictícios (mantidos do código original) -->
                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://images.pexels.com/photos/1821095/pexels-photo-1821095.jpeg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2">Naruto</h5>
                        </div>
                        <p>NÃO É DESENHO, É ANIMEEEEEEEEEE</p>
                        <img class="rounded-4" src="https://i.pinimg.com/736x/73/05/8a/73058a0ea0ee64daf73b7f00e2e09ca5.jpg" width="300px" alt="post01">
                    </div>

                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2">Arigato</h5>
                        </div>
                        <p>É ONICHANNNNNNNNNNNNNNNNN</p>
                    </div>

                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://images.pexels.com/photos/406014/pexels-photo-406014.jpeg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2">minnie</h5>
                        </div>
                        <p>Falam que anime é 'desenho', mas onde mais você encontra uma história que mistura filosofia, política complexa e batalhas épicas, tudo na mesma obra? De slice of life a sci-fi existencial, o anime não tem limites. É o único lugar onde a imaginação realmente manda.</p>
                        <img class="rounded-4" src="https://i.pinimg.com/736x/b5/af/9e/b5af9e4469bfbe8c23b1e43b8dfa0062.jpg" width="300px" alt="post01">
                    </div>
                </div>
            </div>

            <!-- terceira coluna -->
            <div class="col-12 col-md-3">
                <div class="m-4 p-3 text-center" style="border: 2px solid #289256;">
                    <a href="../user.php" style="text-decoration: none; color: inherit;">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" alt="avatar" class="rounded-circle mb-3" width="90" height="90">
                        <h5><?= htmlspecialchars($usuario_nome) ?></h5>
                        <p class="text-muted">Ver meu perfil</p>
                    </a>
                    
                    <!--menu -->
                    <div class="mt-3">
                        <a href="../home.php" class="btn btn-outline-success btn-sm w-100 mb-2">Início</a>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <a href="../admin.php" class="btn btn-outline-primary btn-sm w-100 mb-2">Painel Admin</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>