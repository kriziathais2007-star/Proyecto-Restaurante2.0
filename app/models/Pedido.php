<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPedidos(): array {
    $sql = "SELECT
                p.*,
                u.nombre AS nombre_usuario,
                GROUP_CONCAT(
                    CONCAT(pl.nombre, ' x', dp.cantidad)
                    ORDER BY pl.nombre
                    SEPARATOR '||'
                ) AS platos,
                COALESCE(SUM(dp.cantidad * pl.precio), 0) AS total
            FROM pedido p
            INNER JOIN usuario u          ON p.Id_Usuario = u.Id_Usuario
            LEFT  JOIN detalle_pedido dp  ON p.Id_Pedido  = dp.Id_Pedido
            LEFT  JOIN plato pl           ON dp.Id_Plato  = pl.Id_Plato
            GROUP BY p.Id_Pedido
            ORDER BY p.Id_Pedido DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

    public function obtenerUsuarios(): array {
        $sql = "SELECT Id_Usuario, nombre FROM usuario ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatos(): array {
        $sql = "SELECT Id_Plato, nombre, precio FROM plato WHERE disponibilidad = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // fecha y hora automáticas con CURDATE() y NOW()
    public function crearPedidoConPlatos(int $mesa, string $estado_pedido, int $Id_Usuario, array $platos): bool {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO pedido (mesa, fecha, hora_pedido, estado_pedido, Id_Usuario)
                    VALUES (:mesa, CURDATE(), NOW(), :estado_pedido, :Id_Usuario)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':mesa'          => $mesa,
                ':estado_pedido' => $estado_pedido,
                ':Id_Usuario'    => $Id_Usuario,
            ]);

            $Id_Pedido = (int) $this->db->lastInsertId();

            $sqlDetalle = "INSERT INTO detalle_pedido (cantidad, estado_plato, Id_Pedido, Id_Plato)
                           VALUES (:cantidad, 'Pendiente', :Id_Pedido, :Id_Plato)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            foreach ($platos as $plato) {
                if (!empty($plato['id']) && !empty($plato['cantidad']) && (int)$plato['cantidad'] > 0) {
                    $stmtDetalle->execute([
                        ':cantidad'  => (int)$plato['cantidad'],
                        ':Id_Pedido' => $Id_Pedido,
                        ':Id_Plato'  => (int)$plato['id'],
                    ]);
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function cambiarEstado(int $Id_Pedido, string $estado): bool {
        $estados = ['Pendiente', 'En preparación', 'Servido', 'Entregado'];
        if (!in_array($estado, $estados)) return false;

        $sql = "UPDATE pedido SET estado_pedido = :estado WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':estado' => $estado, ':id' => $Id_Pedido]);
    }
}