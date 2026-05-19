<?php
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ControllerExcel {
    private function requireAuth() {
        if (!isset($_SESSION['usuario'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'mensaje' => 'No autenticado']);
            exit;
        }
    }
    
    public function descargarExcelReservas() {
        $this->requireAuth();

        require_once 'vendor/autoload.php';
        require_once 'models/reserva.php';

        $reservas    = (new Reserva())->getReservasByUser($_SESSION['usuario']['id']);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $hoja        = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Mis Reservas');

        include_once 'view/reports/excel.php';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="mis_reservas.xlsx"');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
}
?>