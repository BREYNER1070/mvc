<?php
class ControllerReserva {

    private function requireAuth() {
        if (!isset($_SESSION['usuario'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'mensaje' => 'No autenticado']);
            exit;
        }
    }

    // ─── AJAX: GET categorías ────────────────────────────────────────────────
    public function getCategorias() {
        header('Content-Type: application/json');
        $this->requireAuth();
        require_once "models/reserva.php";
        echo json_encode(['success' => true, 'categorias' => (new Reserva())->getCategorias()]);
        exit;
    }

    // ─── AJAX: GET habitaciones disponibles ──────────────────────────────────
    public function getHabitacionesDisponibles() {
        header('Content-Type: application/json');
        $this->requireAuth();

        $cat  = $_POST['categoria_id'] ?? '';
        $fi   = $_POST['fecha_inicio'] ?? '';
        $ff   = $_POST['fecha_final']  ?? '';
        $excl = $_POST['excluir_id']   ?? null;

        if (!$cat || !$fi || !$ff) {
            echo json_encode(['success' => false, 'mensaje' => 'Faltan datos']); exit;
        }
        if ($ff <= $fi) {
            echo json_encode(['success' => false, 'mensaje' => 'La fecha de salida debe ser posterior']); exit;
        }

        require_once "models/reserva.php";
        $habs = (new Reserva())->getHabitacionesDisponibles($cat, $fi, $ff, $excl);
        echo json_encode(['success' => true, 'habitaciones' => $habs]);
        exit;
    }

    // ─── AJAX: CREATE ────────────────────────────────────────────────────────
    public function crearReserva() {
        header('Content-Type: application/json');
        $this->requireAuth();

        $fi  = trim($_POST['fecha_inicio']    ?? '');
        $ff  = trim($_POST['fecha_final']     ?? '');
        $np  = trim($_POST['numero_personas'] ?? '');
        $cat = trim($_POST['categoria_id']    ?? '');
        $hab = trim($_POST['habitacion_id']   ?? '');

        $err = $this->_validarFechasPersonas($fi, $ff, $np);
        if (!$cat) $err['categoria_id']  = 'Selecciona una categoría';
        if (!$hab) $err['habitacion_id'] = 'Selecciona una habitación';
        if ($err)  { echo json_encode(['success' => false, 'errores' => $err]); exit; }

        require_once "models/reserva.php";
        $m = new Reserva();

        // Usar estado "Disponible" para reservas nuevas (es el único estado activo)
        $estado_id = $m->getEstadoDisponible();
        if (!$estado_id) {
            echo json_encode(['success' => false, 'mensaje' => 'No se encontró el estado en la base de datos.']); exit;
        }

        [$precio, $noches] = $this->_calcularPrecio($m, $cat, $fi, $ff, $hab);

        $res = $m->crearReserva([
            'fecha_inicio'    => $fi,
            'fecha_final'     => $ff,
            'numero_personas' => $np,
            'precio'          => $precio,
            'categoria_id'    => $cat,
            'estado_id'       => $estado_id,
            'habitacion_id'   => $hab,
            'user_id'         => $_SESSION['usuario']['id'],
        ]);

        if ($res > 0) {
            echo json_encode([
                'success' => true,
                'mensaje' => '¡Reserva confirmada! Nos vemos pronto, ' . htmlspecialchars($_SESSION['usuario']['nombre']) . '.',
                'noches'  => $noches,
                'precio'  => number_format($precio, 2, '.', ','),
            ]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la reserva. Intenta de nuevo.']);
        }
        exit;
    }

    // ─── AJAX: GET una reserva para edición ──────────────────────────────────
    public function getReserva() {
        header('Content-Type: application/json');
        $this->requireAuth();

        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'mensaje' => 'ID inválido']); exit; }

        require_once "models/reserva.php";
        $r = (new Reserva())->getReservaById($id, $_SESSION['usuario']['id']);

        if (!$r) {
            echo json_encode(['success' => false, 'mensaje' => 'Reserva no encontrada']); exit;
        }
        if ($r['estado'] === 'Cancelada') {
            echo json_encode(['success' => false, 'mensaje' => 'No puedes editar una reserva cancelada']); exit;
        }

        echo json_encode(['success' => true, 'reserva' => $r]);
        exit;
    }

    // ─── AJAX: UPDATE ────────────────────────────────────────────────────────
    public function actualizarReserva() {
        header('Content-Type: application/json');
        $this->requireAuth();

        $id  = intval(trim($_POST['id']             ?? 0));
        $fi  = trim($_POST['fecha_inicio']           ?? '');
        $ff  = trim($_POST['fecha_final']            ?? '');
        $np  = trim($_POST['numero_personas']        ?? '');
        $cat = trim($_POST['categoria_id']           ?? '');
        $hab = trim($_POST['habitacion_id']          ?? '');

        if (!$id) { echo json_encode(['success' => false, 'mensaje' => 'ID inválido']); exit; }

        $err = $this->_validarFechasPersonas($fi, $ff, $np);
        if (!$cat) $err['categoria_id']  = 'Selecciona una categoría';
        if (!$hab) $err['habitacion_id'] = 'Selecciona una habitación';
        if ($err)  { echo json_encode(['success' => false, 'errores' => $err]); exit; }

        require_once "models/reserva.php";
        $m = new Reserva();

        $actual = $m->getReservaById($id, $_SESSION['usuario']['id']);
        if (!$actual || $actual['estado'] === 'Cancelada') {
            echo json_encode(['success' => false, 'mensaje' => 'No puedes editar esta reserva']); exit;
        }

        [$precio, $noches] = $this->_calcularPrecio($m, $cat, $fi, $ff, $hab, $id);

        $res = $m->actualizarReserva($id, $_SESSION['usuario']['id'], [
            'fecha_inicio'    => $fi,
            'fecha_final'     => $ff,
            'numero_personas' => $np,
            'precio'          => $precio,
            'categoria_id'    => $cat,
            'habitacion_id'   => $hab,
        ]);

        if ($res > 0) {
            echo json_encode([
                'success' => true,
                'mensaje' => 'Reserva actualizada correctamente.',
                'noches'  => $noches,
                'precio'  => number_format($precio, 2, '.', ','),
            ]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'No se realizaron cambios.']);
        }
        exit;
    }

    // ─── AJAX: CANCEL ────────────────────────────────────────────────────────
    public function cancelarReserva() {
        header('Content-Type: application/json');
        $this->requireAuth();

        $id = intval($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'mensaje' => 'ID inválido']); exit; }

        require_once "models/reserva.php";
        $res = (new Reserva())->cancelarReserva($id, $_SESSION['usuario']['id']);

        if ($res > 0) {
            echo json_encode(['success' => true, 'mensaje' => 'Reserva cancelada correctamente.']);
            unset($_SESSION['reserva_editando']); // Limpiar cualquier estado de edición
            
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'No se pudo cancelar (ya estaba cancelada o no te pertenece).']);
        }
        exit;
    }

    // ─── Vista: formulario nueva reserva ─────────────────────────────────────
    public function getFormReserva() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . SITE_URL . "index.php?action=getFormLoginUser"); exit;
        }
        include_once "view/html/reserva/form_reserva.php";
    }

    // ─── Vista: mis reservas ─────────────────────────────────────────────────
    public function getMisReservas() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . SITE_URL . "index.php?action=getFormLoginUser"); exit;
        }
        require_once "models/reserva.php";
        $reservas = (new Reserva())->getReservasByUser($_SESSION['usuario']['id']);
        include_once "view/html/reserva/mis_reservas.php";
    }

    // ─── Helpers privados ────────────────────────────────────────────────────
    private function _validarFechasPersonas($fi, $ff, $np) {
        $err = [];
        if (!$fi) $err['fecha_inicio'] = 'La fecha de llegada es obligatoria';
        if (!$ff) $err['fecha_final']  = 'La fecha de salida es obligatoria';
        if ($fi && $ff && $ff <= $fi)
            $err['fecha_final'] = 'La salida debe ser posterior a la llegada';
        if (!$np || intval($np) < 1)
            $err['numero_personas'] = 'Indica el número de personas';
        return $err;
    }

    private function _calcularPrecio($model, $cat, $fi, $ff, $hab_id, $excl = null) {
        $habs = $model->getHabitacionesDisponibles($cat, $fi, $ff, $excl);
        $precio_noche = floatval($_POST['precio_noche'] ?? 0);
        foreach ($habs as $h) {
            if ($h['id'] == $hab_id) { $precio_noche = floatval($h['precio']); break; }
        }
        $noches = (new DateTime($fi))->diff(new DateTime($ff))->days;
        return [$precio_noche * $noches, $noches];
    }
}
?>
