<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    require 'db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        $profissao = $_POST['profissao'];
        $numero_registro = $_POST['numero_registro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $data_hora = $_POST['data_hora'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/[^ @]*@[^ @]*\.(com|com\.br)$/", $email)){
            die("E-mail inválido.");
        }
        $sql = "UPDATE forms SET nome = ?, telefone = ?, celular = ?, email = ?, profissao = ?, numero_registro = ?, cidade = ?, estado = ?, data_hora = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if($stmt === false){
            die("Erro na preparação da declaração: " . $conn->error);
        }
        $stmt->bind_param("sssssssssi", $nome, $telefone, $celular, $email, $profissao, $numero_registro, $cidade, $estado, $data_hora, $id);
        if($stmt->execute()){
            $_SESSION['flash_message'] = "Cadastro atualizado com sucesso.";
            header("Location: view_forms.php");
            exit();
        }else{
            echo "Erro ao atualizar o cadastro: " . $stmt->error;
        }
    }else{
        header("Location: edit_form.php");
        exit();
    }
?>