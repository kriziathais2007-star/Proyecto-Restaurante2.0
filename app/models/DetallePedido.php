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
}
?>