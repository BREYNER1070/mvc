<?php
class Reserva {

    // ─── READ: habitaciones disponibles ──────────────────────────────────────
    public function getHabitacionesDisponibles($categoria_id, $fecha_inicio, $fecha_final, $excluir_reserva_id = null) {
        $conexion = new Conexion();
        $conexion->conectar();
        $categoria_id = intval($categoria_id);
        $fecha_inicio = $conexion->escapar($fecha_inicio);
        $fecha_final  = $conexion->escapar($fecha_final);
        $excluir      = $excluir_reserva_id ? "AND r.id != " . intval($excluir_reserva_id) : "";

        $sql = "SELECT h.id, h.numero_camas, h.descripcion, h.precio, h.max_personas, h.aseo,
                       e.nombre AS estado, c.nombre AS categoria
                FROM habitaciones h
                INNER JOIN estados e    ON h.estado_id    = e.id
                INNER JOIN categorias c ON h.categoria_id = c.id
                WHERE h.categoria_id = $categoria_id
                  AND e.nombre = 'Disponible'
                  AND h.id NOT IN (
                      SELECT r.habitacion_id FROM reservas r
                      WHERE r.habitacion_id IS NOT NULL
                        AND r.estado_id != (SELECT id FROM estados WHERE nombre = 'Cancelada')
                        $excluir
                        AND r.fecha_inicio <= '$fecha_final'
                        AND r.fecha_final  >= '$fecha_inicio'
                  )";

        $conexion->query($sql);
        $result = $conexion->getresult();
        $conexion->desconectar();
        $rows = [];
        if ($result && $result->num_rows > 0)
            while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    // ─── READ: categorías ────────────────────────────────────────────────────
    public function getCategorias() {
        $conexion = new Conexion();
        $conexion->conectar();
        $conexion->query("SELECT id, nombre, descripcion FROM categorias ORDER BY nombre");
        $result = $conexion->getresult();
        $conexion->desconectar();
        $rows = [];
        if ($result && $result->num_rows > 0)
            while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    // ─── CREATE ──────────────────────────────────────────────────────────────
    public function crearReserva($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $fi  = $conexion->escapar($data['fecha_inicio']);
        $ff  = $conexion->escapar($data['fecha_final']);
        $np  = intval($data['numero_personas']);
        $pr  = floatval($data['precio']);
        $cat = intval($data['categoria_id']);
        $est = intval($data['estado_id']);
        $hab = intval($data['habitacion_id']);
        $usr = intval($data['user_id']);

        $sql = "INSERT INTO reservas (fecha_inicio, fecha_final, numero_personas, precio, categoria_id, estado_id, habitacion_id, user_id)
                VALUES ('$fi', '$ff', $np, $pr, $cat, $est, $hab, $usr)";

        $conexion->query($sql);
        $filas = $conexion->getFilasAfectadas();
        $conexion->desconectar();
        return $filas;
    }

    // ─── READ: lista de reservas del usuario ─────────────────────────────────
    public function getReservasByUser($user_id) {
        $conexion = new Conexion();
        $conexion->conectar();
        $uid = intval($user_id);

        $sql = "SELECT r.id, r.fecha_inicio, r.fecha_final, r.numero_personas,
                       r.precio, r.categoria_id, r.habitacion_id, r.estado_id,
                       c.nombre AS categoria, e.nombre AS estado
                FROM reservas r
                INNER JOIN categorias c ON r.categoria_id = c.id
                INNER JOIN estados    e ON r.estado_id    = e.id
                WHERE r.user_id = $uid
                ORDER BY r.fecha_inicio DESC";

        $conexion->query($sql);
        $result = $conexion->getresult();
        $conexion->desconectar();
        $rows = [];
        if ($result && $result->num_rows > 0)
            while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    // ─── READ: una reserva por id ─────────────────────────────────────────────
    public function getReservaById($id, $user_id) {
        $conexion = new Conexion();
        $conexion->conectar();
        $id  = intval($id);
        $uid = intval($user_id);

        $sql = "SELECT r.id, r.fecha_inicio, r.fecha_final, r.numero_personas,
                       r.precio, r.categoria_id, r.habitacion_id, r.estado_id,
                       c.nombre AS categoria, e.nombre AS estado
                FROM reservas r
                INNER JOIN categorias c ON r.categoria_id = c.id
                INNER JOIN estados    e ON r.estado_id    = e.id
                WHERE r.id = $id AND r.user_id = $uid
                LIMIT 1";

        $conexion->query($sql);
        $result = $conexion->getresult();
        $conexion->desconectar();
        if ($result && $result->num_rows > 0) return $result->fetch_assoc();
        return null;
    }

    // ─── UPDATE ──────────────────────────────────────────────────────────────
    public function actualizarReserva($id, $user_id, $data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $id  = intval($id);
        $uid = intval($user_id);
        $fi  = $conexion->escapar($data['fecha_inicio']);
        $ff  = $conexion->escapar($data['fecha_final']);
        $np  = intval($data['numero_personas']);
        $pr  = floatval($data['precio']);
        $cat = intval($data['categoria_id']);
        $hab = intval($data['habitacion_id']);

        // Solo permite editar si NO está cancelada
        $sql = "UPDATE reservas
                SET fecha_inicio='$fi', fecha_final='$ff',
                    numero_personas=$np, precio=$pr,
                    categoria_id=$cat, habitacion_id=$hab
                WHERE id=$id AND user_id=$uid
                  AND estado_id != (SELECT id FROM estados WHERE nombre='Cancelada')";

        $conexion->query($sql);
        $filas = $conexion->getFilasAfectadas();
        $conexion->desconectar();
        return $filas;
    }

    // ─── CANCEL ──────────────────────────────────────────────────────────────
    public function cancelarReserva($id, $user_id) {
        $conexion = new Conexion();
        $conexion->conectar();
        $id  = intval($id);
        $uid = intval($user_id);

        $sql = "UPDATE reservas
                SET estado_id = (SELECT id FROM estados WHERE nombre='Cancelada')
                WHERE id=$id AND user_id=$uid
                  AND estado_id != (SELECT id FROM estados WHERE nombre='Cancelada')";

        $conexion->query($sql);
        $filas = $conexion->getFilasAfectadas();
        $conexion->desconectar();
        return $filas;
    }

    // ─── Helper: obtener el estado_id de "Disponible" (para nuevas reservas) ─
    public function getEstadoDisponible() {
        $conexion = new Conexion();
        $conexion->conectar();
        $conexion->query("SELECT id FROM estados WHERE nombre='Disponible' LIMIT 1");
        $result = $conexion->getresult();
        $conexion->desconectar();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
        return null;
    }
}
?>
