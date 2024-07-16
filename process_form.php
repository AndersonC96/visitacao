<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $ciclo = isset($_POST['ciclo']) ? implode(", ", $_POST['ciclo']) : ''; // Converte o array em uma string, se existir

    $sql = "INSERT INTO forms (nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, observacao, data_hora, representante, ciclo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ssssssssssss", $nome, $numero_registro, $nome_conselho, $profissao, $endereco, $cidade, $estado, $visita, $observacao, $data_hora, $representante, $ciclo);

    if ($stmt->execute()) {
        header("Location: form.php?success=true");
    } else {
        header("Location: form.php?success=false&error=" . urlencode($stmt->error));
    }

    $stmt->close();
    $conn->close();
}
?>
