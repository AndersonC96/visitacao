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
    use PhpOffice\PhpSpreadsheet\Chart\Chart;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
    use PhpOffice\PhpSpreadsheet\Chart\Legend;
    use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
    use PhpOffice\PhpSpreadsheet\Chart\Title;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet as PhpSpreadsheetWorksheet;
    function formatPhoneNumber($phoneNumber) {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($phoneNumber) == 10) {
            return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $phoneNumber);
        } else {
            return preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $phoneNumber);
        }
    }
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
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
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
        $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Arial',
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '0000FF',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        $row = 2;
        while ($row_data = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $row, $row_data['nome']);
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
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':L' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':L' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':L' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'F0F0F0',
                    ],
                ],
            ]);
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
?>
<?php
    $title = "Exportar dados"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Exportar dados para Excel</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="data_inicio" class="form-label"><b>Data de Início</b></label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                            </div>
                            <div class="col-md-6">
                                <label for="data_fim" class="form-label"><b>Data de Término</b></label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim">
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-file-export me-2"></i>Exportar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>