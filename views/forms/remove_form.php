<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ./index.php");
        exit();
    }
    require '../config/db.php';
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE FROM forms WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $_SESSION['flash_message'] = "Registro removido com sucesso.";
        }else{
            $_SESSION['flash_message'] = "Falha ao remover registro.";
        }
    }else{
        $_SESSION['flash_message'] = "ID inválido.";
    }
    header("Location: view_forms.php");
    exit();
?>
