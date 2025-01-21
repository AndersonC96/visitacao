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
                    <h3 class="mb-0"><i class="fas fa-user-plus"></i> Criar Usuário</h3>
                </div>
                <div class="card-body">
                    <!-- Formulário de Criação de Usuário -->
                    <form action="success.php" method="post">
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label"><b>Nome</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="nome" 
                                name="nome" 
                                placeholder="Digite o nome" 
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
                                placeholder="Digite o sobrenome" 
                                required
                                aria-label="Digite o sobrenome"
                            >
                        </div>
                        <!-- Nome de Usuário -->
                        <div class="mb-3">
                            <label for="username" class="form-label"><b>Nome de Usuário</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="username" 
                                name="username" 
                                placeholder="Digite o nome de usuário" 
                                required
                                aria-label="Digite o nome de usuário"
                            >
                        </div>
                        <!-- Senha -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label"><b>Senha</b></label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Digite a senha" 
                                    required
                                    aria-label="Digite a senha"
                                >
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small id="passwordStrength" class="form-text text-muted mt-2">Força da senha: <span class="text-danger">Fraca</span></small>
                        </div>
                        <!-- Administrador -->
                        <div class="mb-3">
                            <label for="isAdmin" class="form-label"><b>Administrador</b></label>
                            <select 
                                class="form-select" 
                                id="isAdmin" 
                                name="is_admin" 
                                required
                                aria-label="Selecione se o usuário será administrador"
                            >
                                <option value="0" selected>Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>
                        <!-- Mensagem de Erro -->
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>
                        <!-- Botão de Envio -->
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const passwordStrength = document.getElementById('passwordStrength');

        // Alternar visibilidade da senha
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        // Indicador de força da senha
        passwordInput.addEventListener('input', function () {
            const value = passwordInput.value;
            const strength = getPasswordStrength(value);
            let strengthText = '';
            let strengthClass = '';
            switch (strength) {
                case 1:
                    strengthText = 'Fraca';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthText = 'Média';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthText = 'Forte';
                    strengthClass = 'text-success';
                    break;
            }
            passwordStrength.innerHTML = `Força da senha: <span class="${strengthClass}">${strengthText}</span>`;
        });

        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 4) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../views/templates/footer.php'; ?>
