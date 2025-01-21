<?php
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    // Obter dados de busca
    $search = $_GET['search'] ?? '';
    // Consulta ao banco de dados
    $sql = "SELECT id, username, nome, sobrenome FROM users";
    if (!empty($search)) {
        $sql .= " WHERE nome LIKE '%$search%' OR sobrenome LIKE '%$search%' OR username LIKE '%$search%'";
    }
    $result = mysqli_query($conn, $sql);
    // Título da Página
    $title = "Ver Usuários";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-5">
    <!-- Título e Botão de Adicionar -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-primary">Usuários Cadastrados</h2>
        <a href="create_user.php" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Adicionar Usuário
        </a>
    </div>
    <!-- Barra de Busca -->
    <form method="get" class="d-flex mb-4">
        <input
            type="text"
            name="search"
            class="form-control me-2"
            placeholder="Buscar por nome ou nome de usuário"
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>
    <!-- Tabela de Usuários -->
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
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="align-middle"><?php echo htmlspecialchars($user['nome']) . ' ' . htmlspecialchars($user['sobrenome']); ?></td>
                            <td class="align-middle text-center"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="align-middle text-center">
                                <?php if ($_SESSION['is_admin'] == 1): ?>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-outline-primary btn-sm me-2" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar Usuário">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="remove_user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-outline-danger btn-sm me-2" 
                                       onclick="return confirm('Tem certeza que deseja remover este usuário?');" 
                                       data-bs-toggle="tooltip" 
                                       title="Remover Usuário">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="../models/user_forms.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-outline-info btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Ver Formulários">
                                        <i class="fas fa-folder-open"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('update') === 'success') {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show mt-3';
            alert.role = 'alert';
            alert.innerHTML = `
                <strong>Sucesso!</strong> Usuário atualizado com sucesso.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.querySelector('.container').prepend(alert);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<?php include '../views/templates/footer.php'; ?>