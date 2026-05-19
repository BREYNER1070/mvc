<?php
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ── Ancho de columnas ─────────────────────────────────────────────
$hoja->getColumnDimension('A')->setWidth(20);
$hoja->getColumnDimension('B')->setWidth(15);
$hoja->getColumnDimension('C')->setWidth(15);
$hoja->getColumnDimension('D')->setWidth(12);
$hoja->getColumnDimension('E')->setWidth(15);
$hoja->getColumnDimension('F')->setWidth(15);

// ── Título del reporte ────────────────────────────────────────────
$hoja->mergeCells('A1:F1');
$hoja->setCellValue('A1', 'Grand Eclat Hotel — Reporte de Reservas');
$hoja->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '8B6914']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
]);
$hoja->getRowDimension(1)->setRowHeight(25);

// ── Encabezados (fila 2) ──────────────────────────────────────────
$hoja->setCellValue('A2', 'Categoría');
$hoja->setCellValue('B2', 'Fecha Inicio');
$hoja->setCellValue('C2', 'Fecha Final');
$hoja->setCellValue('D2', 'Personas');
$hoja->setCellValue('E2', 'Precio');
$hoja->setCellValue('F2', 'Estado');

$hoja->getStyle('A2:F2')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B6914']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D4C9B0']]],
]);
$hoja->getRowDimension(2)->setRowHeight(20);

$fila = 3;
foreach ($reservas as $reserva) {
    $hoja->setCellValue("A" . $fila, $reserva['categoria']);
    $hoja->setCellValue("B" . $fila, date('d/m/Y', strtotime($reserva['fecha_inicio'])));
    $hoja->setCellValue("C" . $fila, date('d/m/Y', strtotime($reserva['fecha_final'])));
    $hoja->setCellValue("D" . $fila, $reserva['numero_personas']);
    $hoja->setCellValue("E" . $fila, number_format($reserva['precio'], 2, '.', ','));
    $hoja->setCellValue("F" . $fila, $reserva['estado']);

    // Filas alternas crema/blanco
    $bgColor = ($fila % 2 === 0) ? 'FAF7F0' : 'FFFFFF';

    $hoja->getStyle("A{$fila}:F{$fila}")->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
        'font' => ['color' => ['rgb' => '2C2C2C'], 'size' => 10],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E8DFC8']]],
    ]);

    // Estado en color según valor
    $estadoColor = strtolower($reserva['estado']) === 'cancelada' ? 'C0392B' : '2D6A4F';
    $hoja->getStyle("F{$fila}")->getFont()->getColor()->setRGB($estadoColor);
    $hoja->getStyle("F{$fila}")->getFont()->setBold(true);

    $fila++;
}
?>