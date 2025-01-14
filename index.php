<?php
    session_start();
    // Redirecionar para o dashboard se o usuário já estiver logado
    if (isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit();
    }
    // Conexão com o banco de dados
    include 'db.php';
    // Variável de erro
    $error = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitizar e validar entradas
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];
        // Consulta SQL para buscar o usuário
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Verificar a senha com MD5 ou password_hash
                if (md5($password) === $row['password']) {
                    // Atualizar a senha para o novo padrão com password_hash
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("si", $newHashedPassword, $row['id']);
                    $updateStmt->execute();
                    // Prosseguir com login
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['is_admin'] = $row['is_admin'];
                    header("Location: dashboard.php");
                    exit();
                } elseif (password_verify($password, $row['password'])) {
                    // Login com senha no novo padrão
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['is_admin'] = $row['is_admin'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Usuário ou senha incorretos.";
                }
            } else {
                $error = "Usuário ou senha incorretos.";
            }
        } else {
            // Logar erro para análise interna
            error_log("Erro na preparação da consulta: " . $conn->error);
            $error = "Ocorreu um erro. Tente novamente mais tarde.";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Pharma - Visitação | Login</title>
    <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
    <div class="container">
        <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" class="login-logo">
        <div class="login-card">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="input-group">
                    <i class="icon-user"></i>
                    <input type="text" name="username" placeholder="Usuário" required>
                </div>
                <div class="input-group">
                    <i class="icon-lock"></i>
                    <input type="password" name="password" placeholder="Senha" required>
                </div>
                <?php if (!empty($error)) { ?>
                    <div class="alert"><?php echo $error; ?></div>
                <?php } ?>
                <div class="buttons">
                    <a href="#" class="forgot-link" style="text-decoration: none;">Esqueceu sua senha?</a>
                    <button type="submit" class="login-button">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>