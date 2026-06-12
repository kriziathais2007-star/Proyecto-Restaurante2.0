<?php
require_once __DIR__ . '/../core/Database.php';

class DetalleEntradaExtra {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerDetallesExtra(): array {
        $stmt = $this->db->prepare("SELECT * FROM detalle_entrada_extra");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleExtraPorId(int $id_detalle_extra): array|false {
        $stmt = $this->db->prepare("SELECT * FROM detalle_entrada_extra WHERE id_detalle_extra = ?");
        $stmt->execute([$id_detalle_extra]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trae las entradas extra de un pedido junto al nombre de la entrada
    public function obtenerDetallesExtraPorPedido(int $id_pedido): array {
        $stmt = $this->db->prepare(
            "SELECT dee.*, e.nombre AS nombre_entrada
             FROM detalle_entrada_extra dee
             LEFT JOIN entrada e ON dee.id_entrada = e.id_entrada
             WHERE dee.id_pedido = ?"
        );
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarDetalleExtra(array $datos): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO detalle_entrada_extra (id_pedido, id_entrada, cantidad, precio_unitario, subtotal) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['id_pedido'],
            $datos['id_entrada'] ?? null,
            $datos['cantidad'],
            $datos['precio_unitario'],
            $datos['subtotal']
        ]);
        return $stmt->rowCount() > 0;
    }

    public function editarDetalleExtra(int $cantidad, float $precio_unitario, float $subtotal, int $id_detalle_extra): bool {
        $stmt = $this->db->prepare("UPDATE detalle_entrada_extra SET cantidad = ?, precio_unitario = ?, subtotal = ? WHERE id_detalle_extra = ?");
        $stmt->execute([$cantidad, $precio_unitario, $subtotal, $id_detalle_extra]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdDetalleExtra(string $id_detalle_extra): bool {
        $stmt = $this->db->prepare("DELETE FROM detalle_entrada_extra WHERE id_detalle_extra = ?");
        $stmt->execute([$id_detalle_extra]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPedido(int $id_pedido): bool {
        $stmt = $this->db->prepare("DELETE FROM detalle_entrada_extra WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);
        return $stmt->rowCount() > 0;
    }
}