<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar dados atuais do usuário
$query = "SELECT nome_usuario, email FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Se o formulário foi enviado, atualizar os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_nome = trim($_POST["nome_usuario"]);
    $novo_email = trim($_POST["email"]);

    $update = "UPDATE usuarios SET nome_usuario = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "ssi", $novo_nome, $novo_email, $usuario_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Atualizar sessão
    $_SESSION['usuario_nome'] = $novo_nome;
    $_SESSION['email'] = $novo_email;

    header("Location: user.php");
    exit();
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
    <title>Editar Perfil - Lemon</title>
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
    </style>
</head>

<body style="background-color: #FCFADB;">
    <header class="container-fluid py-3" style="background-color: #289256;">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="index.php"> <img src="img/logo.png" alt="Logo" width="100px"> </a>
            </div>
            <div class="col-6 text-center">
                <h1 style="color: white;" class="m-0">Editar Perfil</h1>
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
                        <h3 class="card-title text-center mb-4" style="color: #289256;">Editar Perfil</h3>

                        <form method="post" action="">
                            <div class="mb-4">
                                <label for="nome_usuario" class="form-label">Nome de Usuário:</label>
                                <input type="text" name="nome_usuario" id="nome_usuario" class="form-control custom-input" required value="<?= htmlspecialchars($usuario['nome_usuario']) ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control custom-input" required value="<?= htmlspecialchars($usuario['email']) ?>">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" style="background-color: #289256; border-color: #289256;">Salvar Alterações</button>
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