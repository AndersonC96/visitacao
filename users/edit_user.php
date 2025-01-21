<?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: view_users.php");
        exit();
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Usuário não encontrado.";
        exit;
    }
    $user = $result->fetch_assoc();
    // Define o título da página
    $title = "Editar Usuário";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-edit"></i> Editar Usuário</h3>
                </div>
                <div class="card-body">
                    <form action="update_user.php" method="post" id="editUserForm">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <!-- Nome de Usuário -->
                        <div class="mb-3">
                            <label for="username" class="form-label"><b>Nome de Usuário</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="username" 
                                name="username" 
                                value="<?php echo htmlspecialchars($user['username']); ?>" 
                                required
                                aria-label="Digite o nome de usuário"
                            >
                        </div>
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label"><b>Nome</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nome" 
                                name="nome" 
                                value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" 
                                required
                                aria-label="Digite o nome"
                            >
                        </div>
                        <!-- Sobrenome -->
                        <div class="mb-3">
                            <label for="sobrenome" class="form-label"><b>Sobrenome</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="sobrenome" 
                                name="sobrenome" 
                                value="<?php echo htmlspecialchars($user['sobrenome'] ?? ''); ?>" 
                                required
                                aria-label="Digite o sobrenome"
                            >
                        </div>
                        <!-- Senha -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label"><b>Senha</b></label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Deixe em branco para não alterar"
                                aria-label="Digite a nova senha"
                            >
                            <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
                        </div>
                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="view_users.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Atualizar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('editUserForm');
        const passwordInput = document.getElementById('password');

        // Confirmação ao alterar a senha
        form.addEventListener('submit', function (e) {
            if (passwordInput.value.trim() !== "") {
                const confirmChange = confirm("Tem certeza que deseja alterar a senha do usuário?");
                if (!confirmChange) {
                    e.preventDefault();
                }
            }
        });
        // Exibe alerta de sucesso ou erro baseado na URL
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
        } else if (urlParams.get('update') === 'failure') {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alert.role = 'alert';
            alert.innerHTML = `
                <strong>Erro!</strong> Não foi possível atualizar o usuário. Tente novamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.querySelector('.container').prepend(alert);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../views/templates/footer.php'; ?>