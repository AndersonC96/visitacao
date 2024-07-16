<?php
include 'db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $numero_registro = $_POST['numero_registro'];
        $nome_conselho = $_POST['nome_conselho'];
        $profissao = $_POST['profissao'];
        $endereco = $_POST['endereco'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $visita = $_POST['visita'];
        $observacao = $_POST['observacao'];
        $data_hora = $_POST['data_hora'];
        $representante = $_POST['representante'];
        $user_id = $_POST['user_id'];
        $ciclo = isset($_POST['ciclo']) ? implode(", ", $_POST['ciclo']) : '';
        $sql = "INSERT INTO forms (nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, observacao, data_hora, representante, ciclo, id_usr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt === false){
            die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sssssssssssss", $nome, $numero_registro, $nome_conselho, $profissao, $endereco, $cidade, $estado, $visita, $observacao, $data_hora, $representante, $ciclo, $user_id);
        if($stmt->execute()){
            header("Location: form.php?success=true");
        }else{
            header("Location: form.php?success=false&error=" . urlencode($stmt->error));
        }
        $stmt->close();
        $conn->close();
    }
?>