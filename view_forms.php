<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($user = $result->fetch_assoc()){
        $_SESSION['user_type'] = $user['is_admin'] ? 'admin' : 'user';
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $results_per_page = 10;
    $offset = ($page - 1) * $results_per_page;
    if($_SESSION['user_type'] == 'admin'){
        $total_sql = "SELECT COUNT(*) AS total FROM forms";
        $forms_sql = "SELECT id, nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, data_hora, ciclo, observacao, representante FROM forms LIMIT ?, ?";
        $stmt = $conn->prepare($forms_sql);
        $stmt->bind_param("ii", $offset, $results_per_page);
    }else{
        $total_sql = "SELECT COUNT(*) AS total FROM forms WHERE id_usr = ?";
        $forms_sql = "SELECT id, nome, numero_registro, nome_conselho, profissao, endereco, cidade, estado, visita, data_hora, ciclo, observacao, representante FROM forms WHERE id_usr = ? LIMIT ?, ?";
        $stmt = $conn->prepare($forms_sql);
        $stmt->bind_param("iii", $user_id, $offset, $results_per_page);
    }
    $total_stmt = $conn->prepare($total_sql);
    if($_SESSION['user_type'] != 'admin'){
        $total_stmt->bind_param("i", $user_id);
    }
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $results_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Visualizar Cadastros</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
        <style>
            .table{
                border-collapse: collapse;
                width: 100%;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
            }
            .table thead{
                background-color: #3ea5af;
                color: #ffffff;
            }
            .table thead th{
                padding: 10px 15px;
                text-align: left;
            }
            .table tbody tr:nth-child(odd){
                background-color: #f8f9fa;
            }
            .table tbody tr:hover{
                background-color: #e9ecef;
            }
            .table td{
                padding: 10px 15px;
                border-top: 1px solid #dee2e6;
                vertical-align: middle;
            }
            .btn-primary{
                background-color: #007bff;
                border: none;
                padding: 5px 10px;
                color: white;
                border-radius: 5px;
                cursor: pointer;
            }
            .btn-primary:hover{
                background-color: #0056b3;
            }
            .btn-danger{
                background-color: #dc3545;
                border: none;
                padding: 5px 10px;
                color: white;
                border-radius: 5px;
                cursor: pointer;
            }
            .btn-danger:hover{
                background-color: #c82333;
            }
            .tooltip-inner{
                max-width: 350px;
                white-space: pre-wrap;
            }
            .truncate{
                display: inline-block;
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
            }
            .pagination .page-item.active .page-link {
                background-color: #3ea5af;
                border-color: #3ea5af;
                color: #ffffff;
            }
            .pagination .page-link {
                color: #3ea5af;
            }
            .pagination .page-link:hover {
                color: #007bff;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-custom shadow-sm bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" style="height: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Voltar ao Início</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <h2 class="text-center">Cadastros Realizados</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped mx-auto">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nº de Registro</th>
                            <th>Conselho</th>
                            <th>Especialidade</th>
                            <th>Endereço</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Tipo da visita</th>
                            <th>Ciclo</th>
                            <th>Observações</th>
                            <th>Data/Hora</th>
                            <th>Representante</th>
                            <?php if($_SESSION['user_type'] == 'admin') { ?>
                            <th>Editar</th>
                            <th>Remover</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['numero_registro']) ?></td>
                            <td><?= htmlspecialchars($row['nome_conselho']) ?></td>
                            <td><?= htmlspecialchars($row['profissao']) ?></td>
                            <td><?= htmlspecialchars($row['endereco']) ?></td>
                            <td><?= htmlspecialchars($row['cidade']) ?></td>
                            <td><?= htmlspecialchars($row['estado']) ?></td>
                            <td><?= htmlspecialchars($row['visita']) ?></td>
                            <td><?= htmlspecialchars($row['ciclo']) ?></td>
                            <td>
                                <span class="truncate" data-bs-toggle="tooltip" title="<?= htmlspecialchars($row['observacao']) ?>">
                                    <?= htmlspecialchars($row['observacao']) ?>
                                </span>
                            </td>
                            <!--<td><?= date('d/m/Y H:i', strtotime($row['data_hora'])) ?></td>-->
                            <td><?= date('d/m/y', strtotime($row['data_hora'])) ?></td>
                            <td><?= htmlspecialchars($row['representante']) ?></td>
                            <?php if($_SESSION['user_type'] == 'admin') { ?>
                            <td><a href="edit_form.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Editar</a></td>
                            <td><a href="remove_form.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este cadastro?')">Remover</a></td>
                            <?php } ?>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr><td colspan="11">Nenhum cadastro encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        <?php if($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#telefone').mask('(00) 0000-0000');
                $('#celular').mask('(00) 00000-0000');
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl){
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    </body>
</html>