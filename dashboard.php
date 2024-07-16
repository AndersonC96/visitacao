<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        $nome_do_usuario = $user['nome'];
    }else{
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Painel de Controle</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php"><img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="form.php"><i class="fas fa-edit"></i> Formulário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_forms.php"><i class="fas fa-eye"></i> Ver Formulários</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="export.php"><i class="fas fa-file-export"></i> Exportar</a>
                        </li>
                        <?php if($user['is_admin']) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create_user.php"><i class="fas fa-user-plus"></i> Criar Usuário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_users.php"><i class="fas fa-users"></i> Ver Usuários</a>
                        </li>
                        <?php } ?>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <h1>Bem-vindo, <b style="color: rgb(83 168 177)"><?php echo $nome_do_usuario; ?></b>.</h1>
        </div>
        <script>
            if(new URLSearchParams(window.location.search).get('success') === 'true'){
                alert('Usuário criado com sucesso!');
                window.location.href = 'dashboard.php';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>