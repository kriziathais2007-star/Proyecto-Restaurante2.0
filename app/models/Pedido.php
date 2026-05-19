<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPedidos(): array {
        $sql = "SELECT p.*, u.nombre AS nombre_usuario
                FROM pedido p
                INNER JOIN usuario u ON p.Id_Usuario = u.Id_Usuario
                ORDER BY p.Id_Pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crearPedido(int $mesa, string $fecha, string $hora_pedido, string $estado_pedido, int $Id_Usuario): bool {
        $sql = "INSERT INTO pedido (mesa, fecha, hora_pedido, estado_pedido, Id_Usuario)
                VALUES (:mesa, :fecha, :hora_pedido, :estado_pedido, :Id_Usuario)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':mesa'         => $mesa,
            ':fecha'        => $fecha,
            ':hora_pedido'  => $hora_pedido,
            ':estado_pedido'=> $estado_pedido,
            ':Id_Usuario'   => $Id_Usuario,
        ]);
    }
}
?>