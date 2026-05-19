<?php

class ControllerPDF {

    

    // ── Generar y descargar el PDF ───────────────────────────────────────|───
    public function descargarPDFReserva() {
        require_once 'models/reserva.php';
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { http_response_code(400); echo 'ID inválido.'; exit; }

        $reserva = (new Reserva())->getReservaById($id,$_SESSION['usuario']['id']);
        if (!$reserva) { http_response_code(404); echo 'Reserva no encontrada.'; exit; }

            $usuario       = $_SESSION['usuario'];
            $nombre        = htmlspecialchars($usuario['nombre'] ?? 'Huésped');
            $email         = htmlspecialchars($usuario['correo'] ?? '');
            $fi            = date('d/m/Y', strtotime($reserva['fecha_inicio']));
            $ff            = date('d/m/Y', strtotime($reserva['fecha_final']));
            $noches        = (new DateTime($reserva['fecha_inicio']))->diff(new DateTime($reserva['fecha_final']))->days;
            $categoria     = htmlspecialchars($reserva['categoria']);
            $personas      = intval($reserva['numero_personas']);
            $precio        = number_format($reserva['precio'], 2, '.', ',');
            $estado        = htmlspecialchars($reserva['estado']);
            $rid           = intval($reserva['id']);
            $fecha_emision = date('d/m/Y H:i');
            $estado_color  = strtolower($estado) === 'cancelada' ? '#c0392b' : '#2d6a4f';

        include_once 'view/reports/pdf.php';

    }
}
?>
