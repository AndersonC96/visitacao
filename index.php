<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        header("Location: dashboard.php");
        exit();
    }
    include 'db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        if($stmt){
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = $row['is_admin'];
                header("Location: dashboard.php");
                exit();
            }else{
                $error = "Usuário ou senha incorretos.";
            }
        } else {
            $error = "Erro na preparação da consulta: " . $conn->error;
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
            <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" class="login-logo" />
            <div class="login-card">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <div class="buttons">
                        <a href="#" class="register-link"></a>
                        <button type="submit" class="login-button">Login</button>
                    </div>
                </form>
                <?php if (!empty($error)) { ?>
                    <div class="alert"><?php echo $error; ?></div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>