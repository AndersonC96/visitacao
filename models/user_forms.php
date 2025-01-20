<?php
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $user_id = $_GET['id'] ?? null;
    if (!$user_id) {
        header("Location: ./view_users.php");
        exit();
    }
    // Data padrão para exibição geral
    $default_start_date = '2025-01-01';
    // Captura os valores do filtro
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
    // Verifica se o `start_date` foi definido e é menor que a data padrão
    if (!$start_date || $start_date >= $default_start_date) {
        $start_date = $default_start_date;
    }
    $page = $_GET['page'] ?? 1; // Página atual
    $results_per_page = 10; // Número de resultados por página
    $offset = ($page - 1) * $results_per_page;
    // Determina o número total de registros
    $count_sql = "SELECT COUNT(*) AS total FROM forms WHERE id_usr = ? AND data_hora >= ?";
    $params = [$user_id, $start_date];
    $types = "is";
    if ($end_date) {
        $count_sql .= " AND data_hora <= ?";
        $params[] = $end_date;
        $types .= "s";
    }
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_results = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_results / $results_per_page);
    // Define a consulta SQL com paginação
    $sql = "SELECT * FROM forms WHERE id_usr = ? AND data_hora >= ?";
    if ($end_date) {
        $sql .= " AND data_hora <= ?";
    }
    $sql .= " ORDER BY data_hora DESC LIMIT ? OFFSET ?";
    $params[] = $results_per_page;
    $params[] = $offset;
    $types .= "ii";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = "Formulários do Usuário";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-5">
    <h2 class="fw-bold text-primary text-center">
        Formulários de 
        <?php
            if ($result->num_rows > 0) {
                $firstRow = $result->fetch_assoc(); // Obtém a primeira linha para exibir o nome
                echo htmlspecialchars($firstRow['representante']);
                $result->data_seek(0); 
            } else {
                echo "Usuário Não Encontrado";
            }
        ?>
    </h2>
    <!-- Botão "Voltar" -->
    <div class="mb-4">
        <a href="../users/view_users.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
    <!-- Filtro por Data -->
    <form method="GET" class="my-4 bg-light p-4 rounded shadow-sm">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_id); ?>">
        <div class="row g-3">
            <div class="col-md-5">
                <label for="start_date" class="form-label"><b>Data Inicial</b></label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
            </div>
            <div class="col-md-5">
                <label for="end_date" class="form-label"><b>Data Final</b></label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Filtrar</button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-sm">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>Data</th>
                    <th>Nome</th>
                    <th>Conselho</th>
                    <th>Nº do Registro</th>
                    <th>Profissão</th>
                    <th>Ciclo</th>
                    <th>Descrição</th>
                    <th>T. Visita</th>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($form = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="align-middle text-center"><?php echo date('d/m/y H:i', strtotime($form['data_hora'])); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['nome']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['nome_conselho']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['numero_registro']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['profissao']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['ciclo']); ?></td>
                        <td class="align-middle"><?php echo htmlspecialchars($form['observacao']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['visita']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['endereco']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['cidade']); ?></td>
                        <td class="align-middle text-center"><?php echo htmlspecialchars($form['estado']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Nenhum formulário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Paginação -->
    <nav>
        <ul class="pagination justify-content-center">
            <!-- Botão "Anterior" -->
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?id=<?php echo htmlspecialchars($user_id); ?>&start_date=<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>&end_date=<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>&page=<?php echo max(1, $page - 1); ?>">
                    &laquo;
                </a>
            </li>
            <?php
                // Número máximo de páginas a exibir
                $max_visible_pages = 5;
                // Determina os limites de páginas a exibir
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                // Exibir a primeira página, se não estiver incluída no intervalo
                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?id=' . htmlspecialchars($user_id) . '&start_date=' . htmlspecialchars($_GET['start_date'] ?? '') . '&end_date=' . htmlspecialchars($_GET['end_date'] ?? '') . '&page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }
                // Exibir as páginas dentro do intervalo
                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?id=' . htmlspecialchars($user_id) . '&start_date=' . htmlspecialchars($_GET['start_date'] ?? '') . '&end_date=' . htmlspecialchars($_GET['end_date'] ?? '') . '&page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
                // Exibir a última página, se não estiver incluída no intervalo
                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?id=' . htmlspecialchars($user_id) . '&start_date=' . htmlspecialchars($_GET['start_date'] ?? '') . '&end_date=' . htmlspecialchars($_GET['end_date'] ?? '') . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }
            ?>
            <!-- Botão "Próximo" -->
            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?id=<?php echo htmlspecialchars($user_id); ?>&start_date=<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>&end_date=<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>&page=<?php echo min($total_pages, $page + 1); ?>">
                    &raquo;
                </a>
            </li>
        </ul>
    </nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php include '../views/templates/footer.php'; ?>