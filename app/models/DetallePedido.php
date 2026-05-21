<?php
require_once __DIR__ . '/../core/Database.php';

class DetallePedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerDetalles(): array {
        $sql = "SELECT dp.*, p.mesa, p.fecha AS fecha_pedido,
                       pl.nombre AS nombre_plato, pl.precio
                FROM detalle_pedido dp
                INNER JOIN pedido p   ON dp.Id_Pedido = p.Id_Pedido
                INNER JOIN plato  pl  ON dp.Id_Plato  = pl.Id_Plato
                ORDER BY dp.Id_Detalle DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPedidos(): array {
        $sql = "SELECT Id_Pedido, mesa, fecha FROM pedido ORDER BY Id_Pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPlatos(): array {
        $sql = "SELECT Id_Plato, nombre, precio FROM plato WHERE disponibilidad = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crearDetalle(int $cantidad, string $estado_plato, int $Id_Pedido, int $Id_Plato): bool {
        $sql = "INSERT INTO detalle_pedido (cantidad, estado_plato, Id_Pedido, Id_Plato)
                VALUES (:cantidad, :estado_plato, :Id_Pedido, :Id_Plato)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cantidad'    => $cantidad,
            ':estado_plato'=> $estado_plato,
            ':Id_Pedido'   => $Id_Pedido,
            ':Id_Plato'    => $Id_Plato,
        ]);
    }
    public function contarPedidosHoy(): int {
    $sql = "SELECT COUNT(*) FROM pedido WHERE fecha = CURDATE()";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

public function ingresosTotalesHoy(): float {
    $sql = "SELECT COALESCE(SUM(dp.cantidad * pl.precio), 0)
            FROM detalle_pedido dp
            INNER JOIN pedido p  ON dp.Id_Pedido = p.Id_Pedido
            INNER JOIN plato  pl ON dp.Id_Plato  = pl.Id_Plato
            WHERE p.fecha = CURDATE()";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return (float) $stmt->fetchColumn();
}

public function pedidosPendientes(): int {
    $sql = "SELECT COUNT(*) FROM pedido WHERE estado_pedido = 'Pendiente'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

public function pedidosRecientes(int $limite = 5): array {
    $sql = "SELECT p.Id_Pedido, p.mesa, p.fecha, p.hora_pedido,
                   p.estado_pedido,
                   COUNT(dp.Id_Detalle) AS total_platos,
                   SUM(dp.cantidad * pl.precio) AS total
            FROM pedido p
            LEFT JOIN detalle_pedido dp ON p.Id_Pedido = dp.Id_Pedido
            LEFT JOIN plato pl          ON dp.Id_Plato = pl.Id_Plato
            GROUP BY p.Id_Pedido
            ORDER BY p.hora_pedido DESC
            LIMIT :limite";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
}