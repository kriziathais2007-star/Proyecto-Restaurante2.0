<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPedidos(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidoPorId(int $id_pedido): array|false {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE id_pedido = ?
        ");
        $stmt->execute([$id_pedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPorEstado(string $estado): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE estado = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosMesa(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE tipo = 'Mesa'
            ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosLlevar(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE tipo = 'Llevar'
            ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPendientes(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE estado = 'Pendiente'
            ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPagados(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE estado = 'Pagado'
            ORDER BY fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPorFecha(string $fecha): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE DATE(fecha) = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarPedido(array $datos): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO pedido (
                id_usuario,
                tipo,
                numero_mesa,
                total,
                estado
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $datos['id_usuario'] ?? null,
            $datos['tipo'],
            $datos['numero_mesa'] ?? null,
            $datos['total'] ?? 0,
            $datos['estado'] ?? 'Pendiente'
        ]);

        return $stmt->rowCount() > 0
            ? (int)$this->db->lastInsertId()
            : false;
    }

    public function editarPedido(
        string $tipo,
        ?int $numero_mesa,
        float $total,
        string $estado,
        int $id_pedido
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE pedido
            SET tipo = ?,
                numero_mesa = ?,
                total = ?,
                estado = ?
            WHERE id_pedido = ?
        ");

        $stmt->execute([
            $tipo,
            $numero_mesa,
            $total,
            $estado,
            $id_pedido
        ]);

        return $stmt->rowCount() > 0;
    }

    public function actualizarEstado(
        int $id_pedido,
        string $estado
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE pedido
            SET estado = ?
            WHERE id_pedido = ?
        ");

        $stmt->execute([
            $estado,
            $id_pedido
        ]);

        return $stmt->rowCount() > 0;
    }

    public function actualizarTotal(
        int $id_pedido,
        float $total
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE pedido
            SET total = ?
            WHERE id_pedido = ?
        ");

        $stmt->execute([
            $total,
            $id_pedido
        ]);

        return $stmt->rowCount() > 0;
    }

    public function sumarAlTotal(
        int $id_pedido,
        float $monto
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE pedido
            SET total = total + ?
            WHERE id_pedido = ?
        ");

        $stmt->execute([
            $monto,
            $id_pedido
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPedido(
        string $id_pedido
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM pedido
            WHERE id_pedido = ?
        ");

        $stmt->execute([$id_pedido]);

        return $stmt->rowCount() > 0;
    }

    public function obtenerPedidoActivoPorMesa(
        int $numero_mesa
    ): array|false {

        $stmt = $this->db->prepare("
            SELECT *
            FROM pedido
            WHERE numero_mesa = ?
            AND tipo = 'Mesa'
            AND estado NOT IN ('Pagado','Cancelado')
            ORDER BY id_pedido DESC
            LIMIT 1
        ");

        $stmt->execute([$numero_mesa]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerMesasOcupadas(): array {

        $stmt = $this->db->prepare("
            SELECT
                id_pedido,
                numero_mesa,
                total,
                estado
            FROM pedido
            WHERE tipo = 'Mesa'
            AND estado NOT IN ('Pagado','Cancelado')
            AND numero_mesa IS NOT NULL
        ");

        $stmt->execute();

        $mesas = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $mesas[(int)$row['numero_mesa']] = $row;
        }

        return $mesas;
    }

    public function contarPedidos(): int {

        $stmt = $this->db->query("
            SELECT COUNT(*) total
            FROM pedido
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarPedidosPendientes(): int {

        $stmt = $this->db->query("
            SELECT COUNT(*) total
            FROM pedido
            WHERE estado <> 'Pagado'
            AND estado <> 'Cancelado'
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarPedidosPagados(): int {

        $stmt = $this->db->query("
            SELECT COUNT(*) total
            FROM pedido
            WHERE estado = 'Pagado'
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerVentasTotales(): float {

        $stmt = $this->db->query("
            SELECT IFNULL(SUM(total),0) total
            FROM pedido
            WHERE estado = 'Pagado'
        ");

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerVentasDelDia(): float {

        $stmt = $this->db->query("
            SELECT IFNULL(SUM(total),0) total
            FROM pedido
            WHERE estado = 'Pagado'
            AND DATE(fecha) = CURDATE()
        ");

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function obtenerVentasDelMes(): float {

        $stmt = $this->db->query("
            SELECT IFNULL(SUM(total),0) total
            FROM pedido
            WHERE estado = 'Pagado'
            AND MONTH(fecha) = MONTH(CURDATE())
            AND YEAR(fecha) = YEAR(CURDATE())
        ");

        return (float)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarMesasOcupadas(): int {

        $stmt = $this->db->query("
            SELECT COUNT(DISTINCT numero_mesa) total
            FROM pedido
            WHERE tipo = 'Mesa'
            AND estado NOT IN ('Pagado','Cancelado')
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarMesasLibres(): int {
        return 21 - $this->contarMesasOcupadas();
    }
}