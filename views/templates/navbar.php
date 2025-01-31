<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="../public/dashboard.php">
            <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" 
                 alt="Logo" style="width: 120px; height: 71px;">
        </a>
        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="../public/dashboard.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'form.php' ? 'active' : ''; ?>" href="../models/form.php">
                        <i class="fas fa-edit"></i> Formulário
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_forms.php' ? 'active' : ''; ?>" href="../models/view_forms.php">
                        <i class="fas fa-eye"></i> Ver Formulários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'export.php' ? 'active' : ''; ?>" href="../controllers/export.php">
                        <i class="fas fa-file-export"></i> Exportar
                    </a>
                </li>
                <?php if (isset($user['is_admin']) && $user['is_admin']) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-cog"></i> Admin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                        <li>
                            <a class="dropdown-item" href="../users/create_user.php">
                                <i class="fas fa-user-plus"></i> Criar Usuário
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../users/view_users.php">
                                <i class="fas fa-users"></i> Ver Usuários
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
            <!-- Logout Button -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link logout-button" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
