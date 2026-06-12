<?php
require_once __DIR__ . '/../core/Database.php';

class Pago {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPagos(): array {
        $stmt = $this->db->prepare("SELECT * FROM pago ORDER BY fecha_pago DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPagoPorId(int $id_pago): array|false {
        $stmt = $this->db->prepare("SELECT * FROM pago WHERE id_pago = ?");
        $stmt->execute([$id_pago]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPagosPorPedido(int $id_pedido): array {
        $stmt = $this->db->prepare("SELECT * FROM pago WHERE id_pedido = ? ORDER BY fecha_pago DESC");
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarPago(array $datos): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO pago (id_pedido, id_usuario, monto, metodo_pago, foto_yape) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['id_pedido'],
            $datos['id_usuario'] ?? null,
            $datos['monto'],
            $datos['metodo_pago'],
            $datos['foto_yape'] ?? null
        ]);
        return $stmt->rowCount() > 0;
    }

    public function editarPago(float $monto, string $metodo_pago, ?string $foto_yape, int $id_pago): bool {
        $stmt = $this->db->prepare("UPDATE pago SET monto = ?, metodo_pago = ?, foto_yape = ? WHERE id_pago = ?");
        $stmt->execute([$monto, $metodo_pago, $foto_yape, $id_pago]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPago(string $id_pago): bool {
        $stmt = $this->db->prepare("DELETE FROM pago WHERE id_pago = ?");
        $stmt->execute([$id_pago]);
        return $stmt->rowCount() > 0;
    }
}