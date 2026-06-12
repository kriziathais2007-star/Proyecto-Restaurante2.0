<?php
require_once __DIR__ . '/../core/Database.php';

class Pago {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos(): array {
        $stmt = $this->db->prepare("
            SELECT 
                p.id_pago,
                p.monto,
                p.metodo_pago,
                p.foto_yape,
                p.fecha_pago,
                pe.id_pedido,
                pe.tipo,
                pe.numero_mesa,
                pe.total,
                pe.estado AS estado_pedido,
                u.nombre AS nombre_cajero
            FROM pago p
            INNER JOIN pedido pe ON p.id_pedido = pe.id_pedido
            LEFT  JOIN usuario u ON p.id_usuario = u.id_usuario
            ORDER BY p.fecha_pago DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar(array $datos): bool {
        $stmt = $this->db->prepare("
            INSERT INTO pago (id_pedido, id_usuario, monto, metodo_pago, foto_yape)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $datos['id_pedido'],
            $datos['id_usuario'] ?: null,
            $datos['monto'],
            $datos['metodo_pago'],
            $datos['foto_yape'] ?: null,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function eliminar(int $id_pago): bool {
        $stmt = $this->db->prepare("DELETE FROM pago WHERE id_pago = ?");
        $stmt->execute([$id_pago]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerPorId(int $id_pago): array|false {
        $stmt = $this->db->prepare("SELECT * FROM pago WHERE id_pago = ?");
        $stmt->execute([$id_pago]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Pedidos disponibles para pagar (no pagados aún)
    public function obtenerPedidosPendientes(): array {
        $stmt = $this->db->prepare("
            SELECT id_pedido, tipo, numero_mesa, total, estado
            FROM pedido
            WHERE estado NOT IN ('Pagado', 'Cancelado')
            ORDER BY id_pedido DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function resumenHoy(): array { 
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total_pagos, 
        COALESCE(SUM(monto), 0) 
        AS total_recaudado, 
        COALESCE(SUM(metodo_pago = 'Efectivo'), 0) 
        AS pagos_efectivo, COALESCE(SUM(metodo_pago = 'Yape'), 0) 
        AS pagos_yape FROM pago WHERE DATE(fecha_pago) = CURDATE() "); 
        $stmt->execute(); 
        return $stmt->fetch(PDO::FETCH_ASSOC); }

}
