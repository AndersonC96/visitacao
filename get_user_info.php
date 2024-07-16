<?php
    include 'db.php';
    if(isset($_GET['nome'])){
        $nome = $_GET['nome'];
        $sql = "SELECT numero_registro, nome_conselho, profissao, endereco, cidade, estado FROM forms WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $user_info = $result->fetch_assoc();
            echo json_encode($user_info);
        }else{
            echo json_encode([]);
        }
    }
?>