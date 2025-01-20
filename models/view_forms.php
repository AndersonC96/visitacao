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
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user_type'] = $user['is_admin'] ? 'admin' : 'user';
    }
    // Definir data padrão (01/01/2025)
    $default_start_date = '2025-01-01';
    // Obter termo de busca e data inicial
    $search_representante = $_GET['search_representante'] ?? '';
    $start_date = $_GET['start_date'] ?? $default_start_date; // Usa a data padrão se nenhuma for fornecida
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $results_per_page = 10;
    $offset = ($page - 1) * $results_per_page;
    // Se o usuário não inserir uma data menor, manter a data padrão
    if ($start_date < $default_start_date) {
        $start_date = $default_start_date;
    }
    if ($_SESSION['user_type'] == 'admin') {
        $total_sql = "SELECT COUNT(*) AS total FROM forms WHERE representante LIKE ? AND data_hora >= ?";
        $forms_sql = "SELECT id, nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, data_hora, ciclo, observacao, representante FROM forms WHERE representante LIKE ? AND data_hora >= ? ORDER BY data_hora DESC LIMIT ?, ?";
        $stmt = $conn->prepare($forms_sql);
        $search_term = '%' . $search_representante . '%';
        $stmt->bind_param("ssii", $search_term, $start_date, $offset, $results_per_page);
    } else {
        $total_sql = "SELECT COUNT(*) AS total FROM forms WHERE id_usr = ? AND representante LIKE ? AND data_hora >= ?";
        $forms_sql = "SELECT id, nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, data_hora, ciclo, observacao, representante FROM forms WHERE id_usr = ? AND representante LIKE ? AND data_hora >= ? ORDER BY data_hora DESC LIMIT ?, ?";
        $stmt = $conn->prepare($forms_sql);
        $search_term = '%' . $search_representante . '%';
        $stmt->bind_param("issii", $user_id, $search_term, $start_date, $offset, $results_per_page);
    }
    // Total de registros
    $total_stmt = $conn->prepare($total_sql);
    if ($_SESSION['user_type'] == 'admin') {
        $total_stmt->bind_param("ss", $search_term, $start_date);
    } else {
        $total_stmt->bind_param("iss", $user_id, $search_term, $start_date);
    }
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $results_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<?php
    $title = "Visualizar formulários";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-4">
    <h2 class="text-center">Formulários cadastrados</h2>
    <form method="get" class="d-flex justify-content-center mb-3">
        <input
            type="text"
            name="search_representante"
            class="form-control w-50 me-2"
            placeholder="Buscar por Representante"
            value="<?= htmlspecialchars($search_representante) ?>"
        >
        <!--<input
            type="date"
            name="start_date"
            class="form-control w-25 me-2"
            value="<?= htmlspecialchars($start_date) ?>"
        >-->
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm text-center justify-content">
            <thead class="table-primary">
                <tr>
                    <th>Nome</th>
                    <th>Reg.</th>
                    <th>Conselho</th>
                    <th>Especialidade</th>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>UF</th>
                    <th>TP. visita</th>
                    <th>Ciclo</th>
                    <th>Observações</th>
                    <th>Data</th>
                    <th>Rep.</th>
                    <?php if ($_SESSION['user_type'] == 'admin') { ?>
                        <th>Editar</th>
                        <th>Remover</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="justify-content"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['numero_registro']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['nome_conselho']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['profissao']) ?></td>
                            <td>
                                <span class="d-inline-block text-truncate justify-content" style="max-width: 150px;" data-bs-toggle="tooltip" title="<?= htmlspecialchars($row['endereco']) ?>">
                                    <?= htmlspecialchars($row['endereco']) ?>
                                </span>
                            </td>
                            <td class="justify-content"><?= htmlspecialchars($row['cidade']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['estado']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['visita']) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['ciclo']) ?></td>
                            <td>
                                <span class="d-inline-block text-truncate justify-content" style="max-width: 150px;" data-bs-toggle="tooltip" title="<?= htmlspecialchars($row['observacao']) ?>">
                                    <?= htmlspecialchars($row['observacao']) ?>
                                </span>
                            </td>
                            <td class="justify-content"><?= date('d/m/y', strtotime($row['data_hora'])) ?></td>
                            <td class="justify-content"><?= htmlspecialchars($row['representante']) ?></td>
                            <?php if ($_SESSION['user_type'] == 'admin') { ?>
                                <td>
                                    <a href="edit_form.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="../models/remove_form.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este cadastro?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" class="text-center">Nenhum cadastro encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&search_representante=<?= htmlspecialchars($search_representante) ?>" aria-label="Previous">
                            &laquo;
                        </a>
                    </li>
                <?php endif; ?>
                <?php
                $range = 2;
                $start = max(1, $page - $range);
                $end = min($total_pages, $page + $range);
                if ($start > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1&search_representante=<?= htmlspecialchars($search_representante) ?>">1</a>
                    </li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search_representante=<?= htmlspecialchars($search_representante) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $total_pages ?>&search_representante=<?= htmlspecialchars($search_representante) ?>"><?= $total_pages ?></a>
                    </li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search_representante=<?= htmlspecialchars($search_representante) ?>" aria-label="Next">
                            &raquo;
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php include '../views/templates/footer.php'; ?>