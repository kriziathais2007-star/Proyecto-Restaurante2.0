<?php
require_once __DIR__ . '/../core/Database.php';

class Entrada {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerEntradas(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM entrada
            ORDER BY nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEntradasActivas(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM entrada
            WHERE activo = 1
            ORDER BY nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEntradaPorId(int $id_entrada): array|false {
        $stmt = $this->db->prepare("
            SELECT *
            FROM entrada
            WHERE id_entrada = ?
        ");
        $stmt->execute([$id_entrada]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarEntrada(array $datos): bool {
        $stmt = $this->db->prepare("
            INSERT INTO entrada(
                nombre,
                precio,
                activo
            )
            VALUES(?,?,?)
        ");

        $stmt->execute([
            $datos['nombre'],
            $datos['precio'],
            $datos['activo'] ?? 1
        ]);

        return $stmt->rowCount() > 0;
    }

    public function editarEntrada(
        string $nombre,
        float $precio,
        int $id_entrada
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE entrada
            SET nombre = ?,
                precio = ?
            WHERE id_entrada = ?
        ");

        $stmt->execute([
            $nombre,
            $precio,
            $id_entrada
        ]);

        return $stmt->rowCount() > 0;
    }

    public function cambiarEstado(
        int $id_entrada,
        bool $activo
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE entrada
            SET activo = ?
            WHERE id_entrada = ?
        ");

        $stmt->execute([
            $activo,
            $id_entrada
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarEntrada(
        int $id_entrada
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM entrada
            WHERE id_entrada = ?
        ");

        $stmt->execute([$id_entrada]);

        return $stmt->rowCount() > 0;
    }

    public function contarEntradas(): int {
        $stmt = $this->db->query("
            SELECT COUNT(*) total
            FROM entrada
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}