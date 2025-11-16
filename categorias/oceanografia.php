<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_nome = $_SESSION['usuario_nome'];
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
    <title>Lemon - Feed</title>
</head>

<body style="background-color: #FCFADB;">
    <header class="container-fluid py-3" style="background-color: #289256;">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="../home.php"> <img src="../img/logo.png" alt="Logo" width="100px"> </a>
            </div>
            <div class="col-6 text-center">
                <h1 style="color: white; text-transform: uppercase;" class="m-0">Oceanografia</h1>
            </div>
            <div class="col-3 text-end">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
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
                <!-- post -->
                <div class="post-box">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" alt="avatar" class="rounded-circle" width="40" height="40">
                        <h5 class="ms-2"><?= ($usuario_nome) ?></h5>
                    </div>

                    <form>
                        <textarea id="postInput" class="input-post" placeholder="O que está acontecendo?" maxlength="280"></textarea>
                        <input class="post-button m-2" type="button" value="Postar" onclick="Postar()">
                    </form>

                    <script>
                        function Postar() {
                            var post = document.getElementById('postInput').value;
                            if (post.trim() !== '') {
                                alert(`Sua postagem foi: ${post}`);
                            } else {
                                alert('Digite algo para postar!');
                            }
                        }
                    </script>
                </div>

                <!-- posts dos usuários -->
                <div id="postsContainer">
                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://images.pexels.com/photos/1821095/pexels-photo-1821095.jpeg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2"> baleiao </h5>
                        </div>
                        <p>já pararam pra pensar no quão grande é o oceano?</p>
                        <img class="rounded-4" src="https://i.pinimg.com/1200x/a2/52/da/a252dae142c7bb21ade3ba5ac9905b36.jpg" width="300px" alt="post01">
                    </div>

                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://i.pinimg.com/736x/45/79/fc/4579fc7247d2291f006af339dfbbf38a.jpg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2"> biologo</h5>
                        </div>
                        <p> CURIOSIDADE!! A parte mais funda da Terra, a Fossa das Marianas, tem 11 mil metros de profundidade — se você colocasse o Monte Everest lá dentro, ele ainda ficaria submerso por mais de 2 km.
                        </p>
                        <img class="rounded-4" src="https://i.pinimg.com/1200x/5e/eb/67/5eeb673a25b5c98d2bed191311a1cd70.jpg" width="300px" alt="post01">
                    </div>

                    <div class="post">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://images.pexels.com/photos/406014/pexels-photo-406014.jpeg" alt="avatar" class="rounded-circle" width="40" height="40">
                            <h5 class="ms-2"> prof dylan </h5>
                        </div>
                        <p>DUVIDO QUE VCS SABIAM DISSO: Mais de 90% do calor retido pelo efeito estufa é absorvido pelo oceano. Se não fosse isso, a Terra seria inabitável..</p>
                        <img class="rounded-4" src="https://i.pinimg.com/1200x/ef/a6/a7/efa6a7bc6daaa15788de210a5cacd219.jpg" width="300px" alt="post01">
                    </div>
                    
                </div>
            </div>

            <!-- terceira coluna -->
            <div class="col-12 col-md-3">
                <div class="m-4 p-3 text-center" style="border: 2px solid #289256;">
                    <a href="user.php" style="text-decoration: none; color: inherit;">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" alt="avatar" class="rounded-circle mb-3" width="90" height="90">
                        <h5><?= htmlspecialchars($usuario_nome) ?></h5>
                        <p class="text-muted">Ver meu perfil</p>
                    </a>
                    
                    <!--menu -->
                    <div class="mt-3">
                        <a href="home.php" class="btn btn-outline-success btn-sm w-100 mb-2">Início</a>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <a href="admin.php" class="btn btn-outline-primary btn-sm w-100 mb-2">Painel Admin</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>