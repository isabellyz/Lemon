<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = '';
$tipo_mensagem = ''; // success ou danger

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = trim($_POST["senha_atual"]);
    $nova_senha = trim($_POST["nova_senha"]);
    $confirmar_senha = trim($_POST["confirmar_senha"]);

    // Verificar se a nova senha e confirmação coincidem
    if ($nova_senha !== $confirmar_senha) {
        $mensagem = "A nova senha e a confirmação não coincidem!";
        $tipo_mensagem = "danger";
    } else {
        // Buscar senha atual do usuário
        $query = "SELECT senha FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Verificar se a senha atual está correta
        if (password_verify($senha_atual, $usuario['senha'])) {
            // Criptografar a nova senha
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            // Atualizar a senha no banco de dados
            $update = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($stmt, "si", $nova_senha_hash, $usuario_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Redirecionar para user.php após sucesso
                header("Location: user.php");
                exit();
            } else {
                $mensagem = "Erro ao alterar a senha. Tente novamente.";
                $tipo_mensagem = "danger";
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensagem = "Senha atual incorreta!";
            $tipo_mensagem = "danger";
        }
    }
}
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
    <title>Alterar Senha - Lemon</title>
    <style>
        .custom-input {
            background-color: #FCFADB !important;
            border: 2px solid #289256 !important;
            text-align: center;
            width: 100% !important;
        }
        .custom-input:focus {
            background-color: #FCFADB !important;
            border-color: #1e7a48 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 146, 86, 0.25);
        }
        .form-label {
            text-align: center;
            display: block;
            width: 100%;
            font-weight: bold;
            color: #289256;
        }
        .form-container {
            max-width: 100%;
        }
    </style>
</head>

<body style="background-color: #FCFADB;">
    <header class="container-fluid py-3" style="background-color: #289256;">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="index.php"> <img src="img/logo.png" alt="Logo" width="100px"> </a>
            </div>
            <div class="col-6 text-center">
                <h1 style="color: white;" class="m-0">Alterar Senha</h1>
            </div>
            <div class="col-3 text-end p-4">
                <a href="user.php" class="btn btn-outline-light btn-sm">Voltar ao Perfil</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card" style="border: 2px solid #289256;">
                    <div class="card-body" style="background-color: #FCFADB; padding: 30px;">
                        <h3 class="card-title text-center mb-4" style="color: #289256;">Alterar Senha</h3>
                        
                        <?php if ($mensagem): ?>
                            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
                                <?= $mensagem ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <div class="mb-4">
                                <label for="senha_atual" class="form-label">Senha Atual</label>
                                <input type="password" name="senha_atual" id="senha_atual" class="form-control custom-input" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="nova_senha" class="form-label">Nova Senha</label>
                                <input type="password" name="nova_senha" id="nova_senha" class="form-control custom-input">
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control custom-input">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" style="background-color: #289256; border-color: #289256;">Alterar Senha</button>
                                <a href="user.php" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>