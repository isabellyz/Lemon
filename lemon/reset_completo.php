<?php
require_once 'conexao.php';

echo "<h3>Corrigindo usuário Admin - MÉTODO CORRETO</h3>";

// Gerar hash CORRETO para a senha "admin"
$senha_plana = "admin";
$senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

echo "Senha original: " . $senha_plana . "<br>";
echo "Hash gerado: " . $senha_hash . "<br>";
echo "Tamanho do hash: " . strlen($senha_hash) . " caracteres<br>";

// Atualizar o usuário Admin no banco
$query = "UPDATE usuarios SET senha = ?, is_admin = 1 WHERE nome_usuario = 'Admin'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $senha_hash);

if (mysqli_stmt_execute($stmt)) {
    echo "✅ Senha do Admin atualizada com SUCESSO!<br><br>";
    
    // VERIFICAR se funcionou
    $query_verify = "SELECT nome_usuario, senha, is_admin FROM usuarios WHERE nome_usuario = 'Admin'";
    $result = mysqli_query($conn, $query_verify);
    $usuario = mysqli_fetch_assoc($result);
    
    echo "<strong>Verificação no banco:</strong><br>";
    echo "Usuário: " . $usuario['nome_usuario'] . "<br>";
    echo "is_admin: " . $usuario['is_admin'] . "<br>";
    echo "Hash no BD: " . $usuario['senha'] . "<br>";
    
    // TESTAR o password_verify
    $teste_senha = password_verify('admin', $usuario['senha']);
    echo "Teste password_verify('admin'): " . ($teste_senha ? "✅ TRUE" : "❌ FALSE") . "<br><br>";
    
    if ($teste_senha) {
        echo "<h3 style='color: green;'>🎉 AGORA DEVE FUNCIONAR!</h3>";
        echo "<p><strong>Login: Admin</strong><br>";
        echo "<strong>Senha: admin</strong></p>";
    } else {
        echo "<h3 style='color: red;'>❌ AINDA HÁ PROBLEMA NO HASH</h3>";
    }
    
} else {
    echo "❌ Erro ao atualizar: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo "<hr>";
echo "<p><a href='login.php' style='color: blue;'>➡️ Ir para a página de Login</a></p>";
?>