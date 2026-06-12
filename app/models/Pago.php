<?php
require_once __DIR__ . '/../core/Database.php';

class Pago {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPagos(): array {

        $stmt = $this->db->prepare("
            SELECT
                pa.*,
                p.tipo,
                p.numero_mesa,
                u.nombre AS usuario
            FROM pago pa
            INNER JOIN pedido p
                ON pa.id_pedido = p.id_pedido
            LEFT JOIN usuario u
                ON pa.id_usuario = u.id_usuario
            ORDER BY pa.fecha_pago DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPagoPorId(
        int $id_pago
    ): array|false {

        $stmt = $this->db->prepare("
            SELECT *
            FROM pago
            WHERE id_pago = ?
        ");

        $stmt->execute([$id_pago]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPagosPorPedido(
        int $id_pedido
    ): array {

        $stmt = $this->db->prepare("
            SELECT *
            FROM pago
            WHERE id_pedido = ?
            ORDER BY fecha_pago DESC
        ");

        $stmt->execute([$id_pedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarPago(
        array $datos
    ): bool {

        $stmt = $this->db->prepare("
            INSERT INTO pago(
                id_pedido,
                id_usuario,
                monto,
                metodo_pago,
                foto_yape
            )
            VALUES(?,?,?,?,?)
        ");

        $stmt->execute([
            $datos['id_pedido'],
            $datos['id_usuario'],
            $datos['monto'],
            $datos['metodo_pago'],
            $datos['foto_yape']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function editarPago(
        float $monto,
        string $metodo_pago,
        ?string $foto_yape,
        int $id_pago
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE pago
            SET monto = ?,
                metodo_pago = ?,
                foto_yape = ?
            WHERE id_pago = ?
        ");

        $stmt->execute([
            $monto,
            $metodo_pago,
            $foto_yape,
            $id_pago
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPago(
        int $id_pago
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM pago
            WHERE id_pago = ?
        ");

        $stmt->execute([$id_pago]);

        return $stmt->rowCount() > 0;
    }

    public function obtenerVentasDelDia(): float {

        $stmt = $this->db->prepare("
            SELECT IFNULL(SUM(monto),0) total
            FROM pago
            WHERE DATE(fecha_pago)=CURDATE()
        ");

        $stmt->execute();

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerVentasDelMes(): float {

        $stmt = $this->db->prepare("
            SELECT IFNULL(SUM(monto),0) total
            FROM pago
            WHERE MONTH(fecha_pago)=MONTH(CURDATE())
            AND YEAR(fecha_pago)=YEAR(CURDATE())
        ");

        $stmt->execute();

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerTotalRecaudado(): float {

        $stmt = $this->db->prepare("
            SELECT IFNULL(SUM(monto),0) total
            FROM pago
        ");

        $stmt->execute();

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarPagos(): int {

        $stmt = $this->db->prepare("
            SELECT COUNT(*) total
            FROM pago
        ");

        $stmt->execute();

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerPagosPorMetodo(): array {

        $stmt = $this->db->prepare("
            SELECT
                metodo_pago,
                COUNT(*) cantidad,
                SUM(monto) total
            FROM pago
            GROUP BY metodo_pago
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimosPagos(
        int $limite = 10
    ): array {

        $stmt = $this->db->prepare("
            SELECT
                pa.*,
                p.tipo,
                p.numero_mesa
            FROM pago pa
            INNER JOIN pedido p
                ON pa.id_pedido = p.id_pedido
            ORDER BY pa.fecha_pago DESC
            LIMIT $limite
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPagosYape(): array {

        $stmt = $this->db->prepare("
            SELECT *
            FROM pago
            WHERE metodo_pago='Yape'
            ORDER BY fecha_pago DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPagosEfectivo(): array {

        $stmt = $this->db->prepare("
            SELECT *
            FROM pago
            WHERE metodo_pago='Efectivo'
            ORDER BY fecha_pago DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}