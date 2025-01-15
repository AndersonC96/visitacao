<?php
    session_start();
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    $sql = "SELECT id, username, nome, sobrenome FROM users";
    $result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Lista de Usuários</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
        <style>
            .action-buttons a{
                margin-right: 5px;
            }
        </style>
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
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Voltar ao Início</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2>Usuários Cadastrados</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover shadow">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome Completo</th>
                            <th>Nome de Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nome']) . ' ' . htmlspecialchars($user['sobrenome']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="action-buttons">
                                <?php if ($_SESSION['is_admin'] == 1): ?>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="remove_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este usuário?');">
                                    <i class="fas fa-trash-alt"></i> Remover
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            if(new URLSearchParams(window.location.search).get('update') === 'success'){
                alert("Usuário atualizado com sucesso!");
                window.location.href = "view_users.php";
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>