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
    date_default_timezone_set('America/Sao_Paulo');
    $hora_atual = date('H');
    if ($hora_atual >= 5 && $hora_atual < 12) {
        $saudacao = "Bom dia";
    } elseif ($hora_atual >= 12 && $hora_atual < 19) {
        $saudacao = "Boa tarde";
    } else {
        $saudacao = "Boa noite";
    }
    // Define o título da página
    $title = "Página Inicial";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-5">
    <!-- Saudação Melhorada -->
    <div class="card shadow-lg border-0">
        <div class="card-body text-center">
            <h1 class="display-5"><?php echo $saudacao; ?>, 
                <span style="color: rgb(83, 168, 177)"><?php echo $nome_do_usuario; ?></span>.
            </h1>
        </div>
    </div>
</div>
<!-- Toast para Feedback Visual -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto text-success">Sucesso</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Usuário criado com sucesso!
        </div>
    </div>
</div>
<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (new URLSearchParams(window.location.search).get('success') === 'true') {
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            toast.show();
        }
    });
</script>
<!-- Scripts do Bootstrap e Jquery -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include '../views/templates/footer.php'; ?>