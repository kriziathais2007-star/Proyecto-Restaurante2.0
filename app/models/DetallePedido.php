<?php
require_once __DIR__ . '/../core/Database.php';

class DetallePedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerDetalles(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM detalle_pedido
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallePorId(int $id_detalle): array|false {
        $stmt = $this->db->prepare("
            SELECT *
            FROM detalle_pedido
            WHERE id_detalle = ?
        ");
        $stmt->execute([$id_detalle]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetallesPorPedido(int $id_pedido): array {
        $stmt = $this->db->prepare("
            SELECT
                dp.*,
                p.nombre AS nombre_plato,
                e.nombre AS nombre_entrada
            FROM detalle_pedido dp
            LEFT JOIN plato p
                ON dp.id_plato = p.id_plato
            LEFT JOIN entrada e
                ON dp.id_entrada = e.id_entrada
            WHERE dp.id_pedido = ?
            ORDER BY dp.id_detalle ASC
        ");

        $stmt->execute([$id_pedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarDetalle(array $datos): bool {

        $stmt = $this->db->prepare("
            INSERT INTO detalle_pedido(
                id_pedido,
                id_plato,
                id_entrada,
                cantidad,
                precio_unitario,
                subtotal
            )
            VALUES(?,?,?,?,?,?)
        ");

        $stmt->execute([
            $datos['id_pedido'],
            $datos['id_plato'] ?? null,
            $datos['id_entrada'] ?? null,
            $datos['cantidad'],
            $datos['precio_unitario'],
            $datos['subtotal']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function editarDetalle(
        int $cantidad,
        float $precio_unitario,
        float $subtotal,
        int $id_detalle
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE detalle_pedido
            SET cantidad = ?,
                precio_unitario = ?,
                subtotal = ?
            WHERE id_detalle = ?
        ");

        $stmt->execute([
            $cantidad,
            $precio_unitario,
            $subtotal,
            $id_detalle
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdDetalle(
        int $id_detalle
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM detalle_pedido
            WHERE id_detalle = ?
        ");

        $stmt->execute([$id_detalle]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPedido(
        int $id_pedido
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM detalle_pedido
            WHERE id_pedido = ?
        ");

        $stmt->execute([$id_pedido]);

        return $stmt->rowCount() > 0;
    }

    public function contarItemsPedido(
        int $id_pedido
    ): int {

        $stmt = $this->db->prepare("
            SELECT COUNT(*) total
            FROM detalle_pedido
            WHERE id_pedido = ?
        ");

        $stmt->execute([$id_pedido]);

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerTotalPedido(
        int $id_pedido
    ): float {

        $stmt = $this->db->prepare("
            SELECT IFNULL(SUM(subtotal),0) total
            FROM detalle_pedido
            WHERE id_pedido = ?
        ");

        $stmt->execute([$id_pedido]);

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerPlatosMasVendidos(): array {

        $stmt = $this->db->prepare("
            SELECT
                p.nombre,
                SUM(dp.cantidad) total_vendidos
            FROM detalle_pedido dp
            INNER JOIN plato p
                ON dp.id_plato = p.id_plato
            WHERE dp.id_plato IS NOT NULL
            GROUP BY p.id_plato
            ORDER BY total_vendidos DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}