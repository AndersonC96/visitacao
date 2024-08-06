<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    if($_SESSION['is_admin'] == 0){
        header("Location: index.php");
        exit();
    }
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Chart\Chart;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
    use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
    use PhpOffice\PhpSpreadsheet\Chart\Legend;
    use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
    use PhpOffice\PhpSpreadsheet\Chart\Title;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet as PhpSpreadsheetWorksheet;
    function formatPhoneNumber($phoneNumber){
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if(strlen($phoneNumber) == 10){
            return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $phoneNumber);
        }else{
            return preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $phoneNumber);
        }
    }
    function formatDateTime($dateTime){
        return date('d/m/Y H:i', strtotime($dateTime));
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
        $data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : null;
        $sql = "SELECT * FROM forms";
        if($data_inicio && $data_fim){
            $sql .= " WHERE data_hora BETWEEN '$data_inicio 00:00:01' AND '$data_fim 23:59:59'";
        }elseif($data_inicio){
            $sql .= " WHERE data_hora >= '$data_inicio 00:00:01'";
        }elseif($data_fim){
            $sql .= " WHERE data_hora <= '$data_fim 23:59:59'";
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
        while($row_data = mysqli_fetch_assoc($result)){
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
        $chartSheet = new PhpSpreadsheetWorksheet($spreadsheet, 'Gráfico');
        $spreadsheet->addSheet($chartSheet);
        $chartSheet->setCellValue('A1', 'Nome do Representante');
        $chartSheet->setCellValue('B1', 'Contagem');
        $representativeCounts = [];
        for($i = 2; $i < $row; $i++){
            $representative = $sheet->getCell('L' . $i)->getValue();
            if(!isset($representativeCounts[$representative])){
                $representativeCounts[$representative] = 0;
            }
            $representativeCounts[$representative]++;
        }
        $chartRow = 2;
        foreach($representativeCounts as $rep => $count){
            $chartSheet->setCellValue('A' . $chartRow, $rep);
            $chartSheet->setCellValue('B' . $chartRow, $count);
            $chartRow++;
        }
        $dataSeriesLabels = [
            new DataSeriesValues('String', 'Gráfico!$B$1', null, 1),
        ];
        $xAxisTickValues = [
            new DataSeriesValues('String', 'Gráfico!$A$2:$A$' . ($chartRow - 1), null, 4),
        ];
        $dataSeriesValues = [
            new DataSeriesValues('Number', 'Gráfico!$B$2:$B$' . ($chartRow - 1), null, 4),
        ];
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
        $plotArea = new PlotArea(null, [$series]);
        $chartTitle = new Title('Contagem de Nome do Representante');
        $chart = new Chart(
            'sample_chart',
            $chartTitle,
            new Legend(Legend::POSITION_RIGHT, null, false),
            $plotArea,
            true,
            0,
            null,
            null
        );
        $chart->setTopLeftPosition('D2');
        $chart->setBottomRightPosition('K15');
        $chartSheet->addChart($chart);
        $filename = 'dados_' . date('d-m-Y_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exportar para Excel</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-custom shadow-sm bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" style="height: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Exportar para Excel</h2>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="mb-3">
                                    <label for="data_inicio" class="form-label">Data de Início</label>
                                    <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                                </div>
                                <div class="mb-3">
                                    <label for="data_fim" class="form-label">Data de Término</label>
                                    <input type="date" class="form-control" id="data_fim" name="data_fim">
                                </div>
                                <button type="submit" class="btn btn-primary">Exportar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>