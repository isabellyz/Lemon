<?php
// Defina as credenciais do banco de dados
$host = 'localhost';  // ou o endereço do seu servidor de banco de dados
$dbname = 'lemon_db';    // nome do banco de dados
$username = 'root';   // nome do usuário do banco de dados
$password = 'usbw';       // senha do banco de dados (geralmente está vazia para XAMPP, mas adicione sua senha se houver)

try {
    // Cria a conexão com o banco de dados
    $conn = new mysqli($host, $username, $password, $dbname);

    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    // Echo de sucesso (só para testes)
    // echo "Conectado com sucesso!";
} catch (Exception $e) {
    // Caso haja erro, mostra a mensagem de erro
    echo "Erro ao conectar: " . $e->getMessage();
    exit();
}
?>
