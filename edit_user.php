<?php
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
        header("Location: view_users.php");
        exit();
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        echo "Usuário não encontrado.";
        exit;
    }
    $user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Editar Usuário</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-custom shadow-sm bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" style="height: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="view_users.php"><i class="fas fa-users"></i> Voltar para usuários</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <h2>Editar Usuário</h2>
            <form action="update_user.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label"><b>Nome de Usuário</b></label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label"><b>Nome</b></label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="sobrenome" class="form-label"><b>Sobrenome</b></label>
                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($user['sobrenome'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><b>Senha</b></label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Deixe em branco para não alterar a senha.</small>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        </div>
        <script>
            if(new URLSearchParams(window.location.search).get('update') === 'failure'){
                alert("Erro ao atualizar o usuário ou nenhuma alteração realizada.");
            }
        </script>
    </body>
</html>