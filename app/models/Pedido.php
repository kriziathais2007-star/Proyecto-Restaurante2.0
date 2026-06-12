<?php
class Pedido {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function obtenerReporte(array $filtros = []): array {
        $sql = "SELECT 
                    p.id_pedido,
                    p.tipo,
                    p.numero_mesa,
                    p.fecha,
                    p.total,
                    p.estado,
                    u.nombre AS nombre_usuario
                FROM pedido p
                LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['fecha_inicio'])) {
            $sql .= " AND p.fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $filtros['fecha_inicio'] . " 00:00:00";
        }

        if (!empty($filtros['fecha_fin'])) {
            $sql .= " AND p.fecha <= :fecha_fin";
            $params[':fecha_fin'] = $filtros['fecha_fin'] . " 23:59:59";
        }

        if (!empty($filtros['tipo'])) {
            $sql .= " AND p.tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND p.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $sql .= " ORDER BY p.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function obtenerResumen(array $filtros = []): array {
        $sql = "SELECT 
                    COUNT(*) AS total_pedidos,
                    SUM(CASE WHEN estado = 'Pagado' THEN total ELSE 0 END) AS total_ventas,
                    SUM(CASE WHEN estado = 'Cancelado' THEN 1 ELSE 0 END) AS total_cancelados
                FROM pedido
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['fecha_inicio'])) {
            $sql .= " AND fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $filtros['fecha_inicio'] . " 00:00:00";
        }

        if (!empty($filtros['fecha_fin'])) {
            $sql .= " AND fecha <= :fecha_fin";
            $params[':fecha_fin'] = $filtros['fecha_fin'] . " 23:59:59";
        }

        if (!empty($filtros['tipo'])) {
            $sql .= " AND tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch();
    }
    public function obtenerEstadoMesas(int $totalMesas = 21): array {
    $sql = "SELECT numero_mesa, id_pedido, estado 
            FROM pedido 
            WHERE tipo = 'Mesa' 
              AND numero_mesa IS NOT NULL 
              AND estado NOT IN ('Pagado','Cancelado')";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $ocupadas = $stmt->fetchAll();

    $mapa = [];
    foreach ($ocupadas as $fila) {
        $mapa[$fila['numero_mesa']] = $fila;
    }

    $mesas = [];
    for ($i = 1; $i <= $totalMesas; $i++) {
        $mesas[] = [
            'numero_mesa' => $i,
            'ocupada'     => isset($mapa[$i]),
            'id_pedido'   => $mapa[$i]['id_pedido'] ?? null,
            'estado'      => $mapa[$i]['estado'] ?? null,
        ];
    }

    return $mesas;
}

public function crearPedido(?int $id_usuario, string $tipo, ?int $numero_mesa = null): int {
    $sql = "INSERT INTO pedido (id_usuario, tipo, numero_mesa, estado) 
            VALUES (:id_usuario, :tipo, :numero_mesa, 'Pendiente')";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':id_usuario'   => $id_usuario,
        ':tipo'         => $tipo,
        ':numero_mesa'  => $numero_mesa,
    ]);
    return (int) $this->db->lastInsertId();
}

public function obtenerPorId(int $id_pedido): ?array {
    $sql = "SELECT * FROM pedido WHERE id_pedido = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id_pedido]);
    $fila = $stmt->fetch();
    return $fila ?: null;
}

public function obtenerPedidosLlevarActivos(): array {
    $sql = "SELECT p.id_pedido, p.fecha, p.total, p.estado, u.nombre AS mesero
            FROM pedido p
            LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
            WHERE p.tipo = 'Llevar' AND p.estado NOT IN ('Pagado','Cancelado')
            ORDER BY p.fecha DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
public function agregarItemPlato(int $id_pedido, int $id_plato, ?int $id_entrada, int $cantidad, float $precio_unitario): void {
    $subtotal = $precio_unitario * $cantidad;

    $sql = "INSERT INTO detalle_pedido (id_pedido, id_plato, id_entrada, cantidad, precio_unitario, subtotal)
            VALUES (:id_pedido, :id_plato, :id_entrada, :cantidad, :precio_unitario, :subtotal)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':id_pedido'       => $id_pedido,
        ':id_plato'        => $id_plato,
        ':id_entrada'      => $id_entrada,
        ':cantidad'        => $cantidad,
        ':precio_unitario' => $precio_unitario,
        ':subtotal'        => $subtotal,
    ]);

    $this->actualizarTotal($id_pedido);
}

public function agregarEntradaExtra(int $id_pedido, int $id_entrada, int $cantidad, float $precio_unitario): void {
    $subtotal = $precio_unitario * $cantidad;

    $sql = "INSERT INTO detalle_entrada_extra (id_pedido, id_entrada, cantidad, precio_unitario, subtotal)
            VALUES (:id_pedido, :id_entrada, :cantidad, :precio_unitario, :subtotal)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':id_pedido'       => $id_pedido,
        ':id_entrada'      => $id_entrada,
        ':cantidad'        => $cantidad,
        ':precio_unitario' => $precio_unitario,
        ':subtotal'        => $subtotal,
    ]);

    $this->actualizarTotal($id_pedido);
}

public function actualizarTotal(int $id_pedido): void {
    $sql = "UPDATE pedido SET total = (
                COALESCE((SELECT SUM(subtotal) FROM detalle_pedido WHERE id_pedido = :id1), 0)
                +
                COALESCE((SELECT SUM(subtotal) FROM detalle_entrada_extra WHERE id_pedido = :id2), 0)
            )
            WHERE id_pedido = :id3";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id1' => $id_pedido, ':id2' => $id_pedido, ':id3' => $id_pedido]);
}

public function obtenerItemsPedido(int $id_pedido): array {
    $sql = "SELECT dp.id_detalle, dp.cantidad, dp.precio_unitario, dp.subtotal,
                   p.nombre AS nombre_plato,
                   e.nombre AS nombre_entrada_incluida
            FROM detalle_pedido dp
            LEFT JOIN plato p ON p.id_plato = dp.id_plato
            LEFT JOIN entrada e ON e.id_entrada = dp.id_entrada
            WHERE dp.id_pedido = :id_pedido
            ORDER BY dp.id_detalle";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id_pedido' => $id_pedido]);
    return $stmt->fetchAll();
}

public function obtenerExtrasPedido(int $id_pedido): array {
    $sql = "SELECT de.id_detalle_extra, de.cantidad, de.precio_unitario, de.subtotal,
                   e.nombre AS nombre_entrada
            FROM detalle_entrada_extra de
            LEFT JOIN entrada e ON e.id_entrada = de.id_entrada
            WHERE de.id_pedido = :id_pedido
            ORDER BY de.id_detalle_extra";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id_pedido' => $id_pedido]);
    return $stmt->fetchAll();
}
public function cambiarEstado(int $id_pedido, string $estado): void {
    $sql = "UPDATE pedido SET estado = :estado WHERE id_pedido = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':estado' => $estado, ':id' => $id_pedido]);
}
public function eliminarPedido(int $id_pedido): bool {
    // Solo permitir eliminar si no está pagado
    $sql = "SELECT estado FROM pedido WHERE id_pedido = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id_pedido]);
    $pedido = $stmt->fetch();

    if (!$pedido || $pedido['estado'] === 'Pagado') {
        return false;
    }

    $this->db->beginTransaction();
    try {
        $this->db->prepare("DELETE FROM detalle_pedido WHERE id_pedido = :id")
                  ->execute([':id' => $id_pedido]);

        $this->db->prepare("DELETE FROM detalle_entrada_extra WHERE id_pedido = :id")
                  ->execute([':id' => $id_pedido]);

        $this->db->prepare("DELETE FROM pago WHERE id_pedido = :id")
                  ->execute([':id' => $id_pedido]);

        $this->db->prepare("DELETE FROM pedido WHERE id_pedido = :id")
                  ->execute([':id' => $id_pedido]);

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        return false;
    }
}
public function pagarPedido(int $id_pedido, float $monto, string $metodo, ?int $id_usuario = null, ?string $fotoYape = null): bool {
    $this->db->beginTransaction();
    try {
        $sql = "INSERT INTO pago (id_pedido, id_usuario, monto, metodo_pago, foto_yape) 
                VALUES (:id_pedido, :id_usuario, :monto, :metodo, :foto)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pedido'  => $id_pedido,
            ':id_usuario' => $id_usuario,
            ':monto'      => $monto,
            ':metodo'     => $metodo,
            ':foto'       => $fotoYape,
        ]);

        $this->cambiarEstado($id_pedido, 'Pagado');

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        return false;
    }
}
}
