<?php
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $sql = "SELECT id, username, nome, sobrenome FROM users";
    $result = mysqli_query($conn, $sql);
?>
<?php
    $title = "Ver usuários"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-5">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-primary">Usuários Cadastrados</h2>
        <a href="create_user.php" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Adicionar Usuário
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-sm">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>Nome Completo</th>
                    <th>Nome de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="align-middle"><?php echo htmlspecialchars($user['nome']) . ' ' . htmlspecialchars($user['sobrenome']); ?></td>
                    <td class="align-middle text-center"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="align-middle text-center">
                        <?php if ($_SESSION['is_admin'] == 1): ?>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="remove_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm me-2" onclick="return confirm('Tem certeza que deseja remover este usuário?');">
                            <i class="fas fa-trash-alt"></i> Remover
                        </a>
                        <a href="../models/user_forms.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-folder-open"></i> Ver Formulários
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
    if (new URLSearchParams(window.location.search).get('update') === 'success') {
        alert("Usuário atualizado com sucesso!");
        window.location.href = "view_users.php";
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>