<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: home.php');
    exit();
}

require_once 'conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    
    // DEBUG
    error_log("=== TENTATIVA DE LOGIN ===");
    error_log("Login: " . $login);
    error_log("Senha: " . $senha);
    
    $query = "SELECT * FROM usuarios WHERE email = ? OR nome_usuario = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $login, $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        
        error_log("Usuário BD: " . $usuario['nome_usuario']);
        error_log("Hash BD: " . $usuario['senha']);
        
        // VERIFICAÇÃO DIRETA - método alternativo utilizando md5
        $senha_hash = md5($senha); // Usando md5 ao invés de password_verify
        if ($senha_hash === $usuario['senha']) {
            error_log("✅ SENHA CORRETA via md5");
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome_usuario'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['is_admin'] = $usuario['is_admin'];
            
            header('Location: home.php');
            exit();
            
        } else {
            error_log("❌ SENHA INCORRETA - md5 não corresponde");
            $erro = "Senha incorreta.";
            
            // TENTATIVA ALTERNATIVA - se o hash estiver corrompido
            if ($senha === "admin" && $usuario['nome_usuario'] === "Admin") {
                error_log("⚠️ Tentando método alternativo para Admin");
                $erro .= " [Tentativa Admin]";
            }
        }
    } else {
        error_log("❌ USUÁRIO NÃO ENCONTRADO");
        $erro = "Usuário não encontrado.";
    }
    
    mysqli_stmt_close($stmt);
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
    <title>Lemon - Login</title>
</head>
<body style="background-color: #FCFADB;">
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="d-flex flex-column justify-content-center align-items-center p-4 border rounded-3" style="width: 500px; background-color: #289256; color: white;">
            <a href="index.php"> <img class="img-fluid align-items-center" src="img/logo.png" width="250px" alt="logo"> </a>
            <form class="d-flex flex-column" action="login.php" method="POST">
                <div class="mb-3">
                    <label for="login" class="form-label">E-mail ou Nome de Usuário</label>
                    <input type="text" class="form-control" id="login" name="login" required value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn custom-btn-start">Entrar</button>
                </div>
            </form> 
            <?php if ($erro): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
