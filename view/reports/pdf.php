<?php
require_once 'fpdf/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// ── ENCABEZADO ──────────────────────────────────────────────────
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(139, 105, 20); // dorado
$pdf->Cell(0, 10, 'Grand Eclat Hotel', 0, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(136, 136, 136);
$pdf->Cell(0, 6, 'Hotel - Cartagena de Indias, Colombia', 0, 1, 'C');

// Línea separadora
$pdf->SetDrawColor(181, 160, 112);
$pdf->SetLineWidth(0.5);
$pdf->Line(20, $pdf->GetY() + 3, 190, $pdf->GetY() + 3);
$pdf->Ln(8);

// ── TÍTULO ───────────────────────────────────────────────────────
$pdf->SetFont('Arial', '', 16);
$pdf->SetTextColor(44, 44, 44);
$pdf->Cell(0, 10, 'Comprobante de Reserva', 0, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(136, 136, 136);
$pdf->Cell(0, 6, 'N. ' . $rid . ' - Emitido el ' . $fecha_emision, 0, 1, 'C');

// Badge estado
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
if (strtolower($estado) === 'cancelada') {
    $pdf->SetFillColor(192, 57, 43);
} else {
    $pdf->SetFillColor(45, 106, 79);
}
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 7, strtoupper($estado), 0, 1, 'C', true);
$pdf->Ln(6);

// ── DATOS DEL HUÉSPED ────────────────────────────────────────────
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(139, 105, 20);
$pdf->Cell(0, 6, 'DATOS DEL HUESPED', 0, 1, 'L');
$pdf->SetDrawColor(232, 223, 200);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(102, 102, 102);
$pdf->Cell(50, 7, 'Nombre', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(26, 26, 26);
$pdf->Cell(0, 7, $nombre, 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(102, 102, 102);
$pdf->Cell(50, 7, 'Correo', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(26, 26, 26);
$pdf->Cell(0, 7, $email, 0, 1);
$pdf->Ln(4);

// ── DETALLES DE LA RESERVA ───────────────────────────────────────
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(139, 105, 20);
$pdf->Cell(0, 6, 'DETALLES DE LA RESERVA', 0, 1, 'L');
$pdf->SetDrawColor(232, 223, 200);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(3);

$detalles = [
    'Llegada'   => $fi,
    'Salida'    => $ff,
    'Duracion'  => $noches . ' noche(s)',
    'Categoria' => $categoria,
    'Personas'  => $personas,
];

foreach ($detalles as $label => $valor) {
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(102, 102, 102);
    $pdf->Cell(50, 7, $label, 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(26, 26, 26);
    $pdf->Cell(0, 7, $valor, 0, 1);
}
$pdf->Ln(4);

// ── TOTAL ────────────────────────────────────────────────────────
$pdf->SetFillColor(250, 247, 240);
$pdf->SetDrawColor(212, 201, 176);
$pdf->Rect(20, $pdf->GetY(), 170, 16, 'DF');  
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(102, 102, 102);
$pdf->Cell(85, 16, '  TOTAL A PAGAR', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(139, 105, 20);
$pdf->Cell(85, 16, '$' . $precio, 0, 1, 'R');
$pdf->Ln(6);

// ── PIE ──────────────────────────────────────────────────────────
$pdf->SetDrawColor(232, 223, 200);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(4);

$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(85, 85, 85);
$pdf->Cell(0, 6, 'Gracias por elegir Grand Eclat!', 0, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(170, 170, 170);
$pdf->Cell(0, 5, 'Grand Eclat Hotel - Cartagena de Indias, Colombia', 0, 1, 'C');
$pdf->Cell(0, 5, '2026 Grand Eclat Hotel. Todos los derechos reservados.', 0, 1, 'C');

// ── DESCARGAR ────────────────────────────────────────────────────
$pdf->Output('D', 'reserva_' . $rid . '.pdf');
exit;
?>