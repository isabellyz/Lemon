<?php
require_once 'conexao.php';
$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = trim($_POST['nome_usuario']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $repetir_senha = trim($_POST['repetir_senha']);

    // Verifica se as senhas coincidem
    if ($senha !== $repetir_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        // Gera hash com md5 (para compatibilidade com PHP antigo)
        $senha_hash = md5($senha);

        // Verifica se usuário ou e-mail já existe
        $query = "SELECT * FROM usuarios WHERE email = ? OR nome_usuario = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $nome_usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $erro = "Usuário ou e-mail já cadastrado.";
        } else {
            // Inserir novo usuário
            $query = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $nome_usuario, $email, $senha_hash);

            if (mysqli_stmt_execute($stmt)) {
                $sucesso = "Usuário cadastrado com sucesso!";
                // Redirecionar para login após 2 segundos
                header("refresh:2;url=login.php");
            } else {
                $erro = "Erro ao cadastrar usuário.";
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
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
    <title>Lemon - Cadastro</title>
</head>
<body style="background-color: #FCFADB;">
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="d-flex flex-column justify-content-center align-items-center p-4 border rounded-3" style="width: 500px; background-color: #289256; color: white;">
            <a href="index.php">
                <img class="img-fluid align-items-center" src="img/logo.png" width="250px" alt="logo">
            </a>

            <form class="d-flex flex-column mt-3 mx-auto" style="width: 100%" action="cadastro.php" method="POST">
                <div class="mb-3">
                    <label for="nome_usuario" class="form-label">Nome de Usuário</label>
                    <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required value="<?php echo isset($_POST['nome_usuario']) ? htmlspecialchars($_POST['nome_usuario']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="mb-3">
                    <label for="repetir_senha" class="form-label">Repetir Senha</label>
                    <input type="password" class="form-control" id="repetir_senha" name="repetir_senha" required>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn custom-btn-start">Cadastrar</button>
                </div>
            </form>

            <?php if ($erro): ?>
                <div class="alert alert-danger mt-3 w-100 text-center" role="alert">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert alert-success mt-3 w-100 text-center" role="alert">
                    <?php echo $sucesso; ?><br>Redirecionando para login...
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
