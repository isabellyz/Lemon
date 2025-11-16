<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: login.php');
    exit();
}

// Obtém o nome do usuário da sessão
$nome_usuario = $_SESSION['usuario_nome'];

// Verifica se o usuário é um administrador
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <title>Lemon - Home</title>
</head>
<body style="background-color: #FCFADB;">
    <header class="header mb-4" style="background-color: #289256;">
        <a href="home.php"><img src="img/logo.png" width="100px" alt="Logo" /> </a>
        <div class="d-flex justify-content-between w-100 align-items-center">
            <div class="mx-auto text-center">
                <span class="fs-2 text-white">Bem-vindo, <?php echo ($nome_usuario); ?>!</span>
            </div>
            
            <div class="d-flex m-4">
                <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
                
                <?php if ($is_admin): ?>
                    <a href="admin.php" class="btn btn-warning btn-sm ms-3">Administração</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container section mb-5">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="coluna text-center">
                    <a href="categorias/pg.php"> <p>Filmes e Séries </p> </a>
                    <a href="categorias/anime.php"> <p>Animes e Mangás</p> </a>
                    <a href="categorias/jogos.php"> <p>Jogos</p> </a> 
                    <a href="categorias/musica.php"> <p>Música</p> </a>
                </div>
            </div>
            <div class="col-md-3">
                <a href="#">
                    <div class="coluna text-center">
                        <a href="categorias/comida.php"> <p>Comida e Bebida</p> </a>
                        <a href="categorias/moda.php"> <p>Moda e Estilo</p> </a>
                        <a href="categorias/esportes.php"> <p>Esportes</p> </a>
                        <a href="categorias/pets.php"> <p>Pets e Animais</p> </a>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#">
                    <div class="coluna text-center">
                        <a href="categorias/livros.php"> <p>Livros e Literatura</p> </a>
                        <a href="categorias/arte.php"> <p>Arte e Design</p> </a>
                        <a href="categorias/culturaMundial.php"> <p>Cultura Mundial</p> </a>
                        <a href="categorias/curiosidades.php"> <p>Curiosidades</p> </a>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container section">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="coluna text-center">
                    <a href="categorias/religiao.php"> <p>Religião </p> </a>   
                    <a href="categorias/fotografia.php"> <p>Fotografia </p> </a>
                    <a href="categorias/astrologia.php"> <p>Astrologia</p> </a>
                    <a href="categorias/contosFabulas.php"> <p>Contos e Fábulas</p> </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="coluna text-center">
                    <a href="categorias/lendasUrbanas.php"> <p>Lendas Urbanas</p> </a>
                    <a href="categorias/mitologia.php"> <p>Mitologia</p> </a>
                    <a href="categorias/autoAjuda.php"> <p>Auto Ajuda</p> </a>
                    <a href="categorias/oceanografia.php"> <p> Oceanogr</p> </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="coluna text-center">
                    <a href="categorias/quadrinhos.php"> <p>Quadrinhos</p> </a> 
                    <a href="categorias/culturaPOP.php"> <p>Cultura POP</p> </a>
                    <a href="categorias/podcast.php"> <p>Podcast</p> </a> 
                    <a href="categorias/programacao.php"> <p>Programação</p> </a>
                </div>
            </div>
        </div>
    </div>
</body>

<footer class="footer">
    <a href="index.php"><img src="img/logo.png" width="100px" alt="Logo do Footer"> </a>
</footer>
</html>
