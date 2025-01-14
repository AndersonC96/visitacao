<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $nome_do_usuario = $user['nome'];
    } else {
        header("Location: ./index.php");
        exit();
    }
    // Determinar a saudação com base no horário atual
    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário
    $hora_atual = date('H'); // Obtém a hora atual no formato 24 horas
    if ($hora_atual >= 5 && $hora_atual < 12) {
        $saudacao = "Bom dia";
    } elseif ($hora_atual >= 12 && $hora_atual < 19) {
        $saudacao = "Boa tarde";
    } else {
        $saudacao = "Boa noite";
    }
?>
<?php
    $title = "Página Inicial"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-5">
    <h1><?php echo $saudacao; ?>, <b style="color: rgb(83 168 177)"><?php echo $nome_do_usuario; ?></b>.</h1>
</div>
<script>
    if (new URLSearchParams(window.location.search).get('success') === 'true') {
        alert('Usuário criado com sucesso!');
        window.location.href = 'dashboard.php';
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>