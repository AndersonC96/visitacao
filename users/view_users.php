<?php
    session_start();
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $sql = "SELECT id, username, nome, sobrenome FROM users";
    $result = mysqli_query($conn, $sql);
?>
<?php
    $title = "Formulário"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
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