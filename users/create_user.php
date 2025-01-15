<?php
    $title = "Criar usuário"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Criar Usuário</h3>
                </div>
                <div class="card-body">
                    <form action="success.php" method="post">
                        <div class="mb-3">
                            <label for="nome" class="form-label"><b>Nome</b></label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="sobrenome" class="form-label"><b>Sobrenome</b></label>
                            <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label"><b>Nome de Usuário</b></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Digite o nome de usuário" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><b>Senha</b></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Digite a senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="isAdmin" class="form-label"><b>Administrador</b></label>
                            <select class="form-select" id="isAdmin" name="is_admin" required>
                                <option value="0" selected>Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Criar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>