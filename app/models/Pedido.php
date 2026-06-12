<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPedidos(): array {
        $stmt = $this->db->prepare("SELECT * FROM pedido ORDER BY fecha DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidoPorId(int $id_pedido): array|false {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPorEstado(string $estado): array {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE estado = ? ORDER BY fecha DESC");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarPedido(array $datos): int|false {
        $stmt = $this->db->prepare("INSERT INTO pedido (id_usuario, tipo, numero_mesa, total, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $datos['id_usuario'] ?? null,
            $datos['tipo'],
            $datos['numero_mesa'] ?? null,
            $datos['total'] ?? 0,
            $datos['estado'] ?? 'Pendiente'
        ]);
        return $stmt->rowCount() > 0 ? (int)$this->db->lastInsertId() : false;
    }

    public function editarPedido(string $tipo, ?int $numero_mesa, float $total, string $estado, int $id_pedido): bool {
        $stmt = $this->db->prepare("UPDATE pedido SET tipo = ?, numero_mesa = ?, total = ?, estado = ? WHERE id_pedido = ?");
        $stmt->execute([$tipo, $numero_mesa, $total, $estado, $id_pedido]);
        return $stmt->rowCount() > 0;
    }

    public function actualizarEstado(int $id_pedido, string $estado): bool {
        $stmt = $this->db->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
        $stmt->execute([$estado, $id_pedido]);
        return $stmt->rowCount() > 0;
    }

    public function actualizarTotal(int $id_pedido, float $total): bool {
        $stmt = $this->db->prepare("UPDATE pedido SET total = ? WHERE id_pedido = ?");
        $stmt->execute([$total, $id_pedido]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPedido(string $id_pedido): bool {
        $stmt = $this->db->prepare("DELETE FROM pedido WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);
        return $stmt->rowCount() > 0;
    }
}