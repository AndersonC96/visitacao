<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT is_admin FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentUser = $result->fetch_assoc();
        if($currentUser['is_admin'] == 1 || $id == $user_id){
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                header("Location: view_users.php?remocao=sucesso");
            }else{
                header("Location: view_users.php?erro=nao-foi-possivel-remover");
            }
        }else{
            header("Location: view_users.php?erro=permissao-negada");
        }
    }else{
        header("Location: view_users.php");
        exit();
    }
?>