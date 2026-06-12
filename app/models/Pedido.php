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
}