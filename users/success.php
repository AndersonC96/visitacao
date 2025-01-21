<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $admin = $_POST['is_admin'];
        $sql = "INSERT INTO users (nome, sobrenome, username, password, is_admin) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt){
            $stmt->bind_param("ssssi", $nome, $sobrenome, $username, $password, $admin);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                header("Location: view_users.php?success=true");
                exit();
            }else{
                echo "Erro ao adicionar o usuário: " . $conn->error;
            }
        }else{
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    }
?>