<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "lemon_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultar todos os usuários cadastrados (SEM created_at)
$sql = "SELECT id, nome_usuario, email, is_admin FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-badge {
            background-color: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">👥 Gerenciar Usuários</h2>
        <div>
            <a href="home.php" class="btn btn-secondary">← Voltar</a>
            <span class="ms-2 badge bg-success">Administrador</span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome de Usuário</th>
                        <th>Email</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $is_admin = $row['is_admin'] == 1;
                            echo "<tr>
                                    <td><strong>#{$row['id']}</strong></td>
                                    <td>{$row['nome_usuario']}</td>
                                    <td>{$row['email']}</td>
                                    <td>";
                            if ($is_admin) {
                                echo "<span class='admin-badge'>Administrador</span>";
                            } else {
                                echo "<span class='badge bg-secondary'>Usuário</span>";
                            }
                            echo "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-muted py-4'>Nenhum usuário cadastrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <?php
            if ($result->num_rows > 0) {
                echo "<div class='mt-3 text-muted'>Total de usuários: <strong>" . $result->num_rows . "</strong></div>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>