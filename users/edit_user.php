<?php
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
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
<?php
    $title = "Formulário"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Editar Usuário</h3>
                </div>
                <div class="card-body">
                    <form action="update_user.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label"><b>Nome de Usuário</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="username" 
                                name="username" 
                                value="<?php echo htmlspecialchars($user['username']); ?>" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label"><b>Nome</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nome" 
                                name="nome" 
                                value="<?php echo htmlspecialchars($user['nome'] ?? ''); ?>" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="sobrenome" class="form-label"><b>Sobrenome</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="sobrenome" 
                                name="sobrenome" 
                                value="<?php echo htmlspecialchars($user['sobrenome'] ?? ''); ?>" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><b>Senha</b></label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Deixe em branco para não alterar"
                            >
                            <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
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
    if(new URLSearchParams(window.location.search).get('update') === 'failure'){
        alert("Erro ao atualizar o usuário ou nenhuma alteração realizada.");
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>