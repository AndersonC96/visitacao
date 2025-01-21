<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    $is_admin = $_SESSION['is_admin'];
    $user_id = $_SESSION['user_id'];
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    // Função para validar e formatar campos de data/hora
    function formatDateTime($dateTime) {
        return date('d/m/Y H:i', strtotime($dateTime));
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
        $data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : null;
        $sql = "SELECT * FROM forms";
        if ($is_admin == 0) {
            $sql .= " WHERE id_usr = $user_id";
        }
        if ($data_inicio && $data_fim) {
            $sql .= ($is_admin == 0 ? " AND" : " WHERE") . " data_hora BETWEEN '$data_inicio 00:00:01' AND '$data_fim 23:59:59'";
        } elseif ($data_inicio) {
            $sql .= ($is_admin == 0 ? " AND" : " WHERE") . " data_hora >= '$data_inicio 00:00:01'";
        } elseif ($data_fim) {
            $sql .= ($is_admin == 0 ? " AND" : " WHERE") . " data_hora <= '$data_fim 23:59:59'";
        }
        $result = mysqli_query($conn, $sql);
        if (!$result || mysqli_num_rows($result) == 0) {
            header("Location: export.php?success=false&error=NoData");
            exit();
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Cabeçalhos
        $sheet->setCellValue('A1', 'Nome');
        $sheet->setCellValue('B1', 'Número de Registro');
        $sheet->setCellValue('C1', 'Conselho');
        $sheet->setCellValue('D1', 'Especialidade');
        $sheet->setCellValue('E1', 'Endereço');
        $sheet->setCellValue('F1', 'Cidade');
        $sheet->setCellValue('G1', 'Estado');
        $sheet->setCellValue('H1', 'Tipo da visita');
        $sheet->setCellValue('I1', 'Brand do Ciclo');
        $sheet->setCellValue('J1', 'Observações');
        $sheet->setCellValue('K1', 'Data e Hora');
        $sheet->setCellValue('L1', 'Nome do Representante');
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0000FF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        $row = 2;
        while ($row_data = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $row, $row_data['nome']);
            //$sheet->setCellValue('B' . $row, $row_data['numero_registro']);
            $sheet->setCellValueExplicit('B' . $row, $row_data['numero_registro'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $row_data['nome_conselho']);
            $sheet->setCellValue('D' . $row, $row_data['profissao']);
            $sheet->setCellValue('E' . $row, $row_data['endereco']);
            $sheet->setCellValue('F' . $row, $row_data['cidade']);
            $sheet->setCellValue('G' . $row, $row_data['estado']);
            $sheet->setCellValue('H' . $row, $row_data['visita']);
            $sheet->setCellValue('I' . $row, $row_data['ciclo']);
            $sheet->setCellValue('J' . $row, $row_data['observacao']);
            $sheet->setCellValue('K' . $row, formatDateTime($row_data['data_hora']));
            $sheet->setCellValue('L' . $row, $row_data['representante']);
            $row++;
        }
        $filename = 'dados_' . date('d-m-Y_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    $title = "Exportar Dados";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
<div class="container mt-5">
    <!-- Feedback Visual -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 'false'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> Nenhum dado encontrado para exportação.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-file-export"></i> Exportar Dados</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="data_inicio" class="form-label">Data de Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                            </div>
                            <div class="col-md-6">
                                <label for="data_fim" class="form-label">Data de Término</label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-download me-2"></i> Exportar para Excel
                        </button>
                    </form>
                    <!-- Barra de Carregamento -->
                    <div id="loading" class="d-none mt-3">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%; height: 25px;">Processando, por favor, aguarde...</div>
                        </div>
                    </div>
                    <!-- Mensagem de Sucesso -->
                    <div id="success-message" class="alert alert-success d-none mt-3" role="alert">
                        <strong>Sucesso!</strong> O arquivo foi gerado e está pronto para download.
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const loadingDiv = document.getElementById('loading');
        const successMessage = document.getElementById('success-message');
        form.addEventListener('submit', function () {
            // Exibe a barra de carregamento
            loadingDiv.classList.remove('d-none');
            successMessage.classList.add('d-none'); // Oculta a mensagem de sucesso
            // Desabilita o botão de envio para evitar múltiplos cliques
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            // Simula o tempo de geração do arquivo antes de mostrar a mensagem
            setTimeout(() => {
                loadingDiv.classList.add('d-none'); // Remove a barra de carregamento
                successMessage.classList.remove('d-none'); // Exibe a mensagem de sucesso
                submitButton.disabled = false; // Reabilita o botão
            }, 3000); // Tempo estimado (ajuste conforme necessário)
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../views/templates/footer.php'; ?>
