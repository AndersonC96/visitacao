<?php
    session_start();
    if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $id = $_POST['id'];
    $username = $_POST['username'];
    $firstName = $_POST['nome'];
    $lastName = $_POST['sobrenome'];
    $password = $_POST['password'];
    $sql = "UPDATE users SET username = ?, nome = ?, sobrenome = ?".(!empty($password) ? ", password = ?" : "")." WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if(!empty($password)){
        $passwordHash = md5($password);
        $stmt->bind_param("ssssi", $username, $firstName, $lastName, $passwordHash, $id);
    }else{
        $stmt->bind_param("sssi", $username, $firstName, $lastName, $id);
    }
    $stmt->execute();
    /*if($stmt->affected_rows > 0){
        header("Location: view_users.php?update=success");
        exit();
    }else{
        echo "Erro ao atualizar o usuário ou nenhuma alteração realizada.";
    }*/
    if($stmt->affected_rows > 0){
        header("Location: view_users.php?update=success");
        exit();
    }else{
        header("Location: edit_user.php?id=$id&update=failure");
        exit();
    }
?>