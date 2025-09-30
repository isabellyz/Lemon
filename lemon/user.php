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
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
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
    <title>Perfil de <?= ($usuario_nome) ?> - Lemon</title>
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
                <a href="pg.php" class="btn btn-outline-light btn-sm">Voltar ao Feed</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="p-3 text-center" style="border: 2px solid #289256;">
                    <h5>Menu</h5>
                    <a href="pg.php" class="d-block py-2">Feed Principal</a>
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
                            <h5>25</h5>
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

                <h4>Meus Posts</h4>
                <div class="mypost mb-3" style="border: 2px solid #289256; padding: 20px;">
                    <div class="d-flex align-items-center mb-2 ">
                        <img src="https://img.freepik.com/vetores-gratis/ilustracao-de-usuario-avatar-icone_53876-5907.jpg" 
                             alt="avatar" class="rounded-circle" width="40" height="40">
                        <h6 class="ms-2"><?= ($usuario_nome) ?></h6>
                    </div>
                    <p>fui lemonado perdão nao pude resistir</p>
                </div>
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
            </div>
        </div>
    </div>
</body>
</html>