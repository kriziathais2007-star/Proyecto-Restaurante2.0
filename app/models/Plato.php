<?php
require_once __DIR__ . '/../core/Database.php';

class Plato {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPlatos(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM plato
            ORDER BY nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatosActivos(): array {
        $stmt = $this->db->prepare("
            SELECT *
            FROM plato
            WHERE activo = 1
            ORDER BY nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatoPorId(int $id_plato): array|false {
        $stmt = $this->db->prepare("
            SELECT *
            FROM plato
            WHERE id_plato = ?
        ");
        $stmt->execute([$id_plato]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarPlato(array $datos): bool {
        $stmt = $this->db->prepare("
            INSERT INTO plato(
                nombre,
                descripcion,
                precio,
                activo
            )
            VALUES(?,?,?,?)
        ");

        $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['activo'] ?? 1
        ]);

        return $stmt->rowCount() > 0;
    }

    public function editarPlato(
        string $nombre,
        string $descripcion,
        float $precio,
        int $id_plato
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE plato
            SET nombre = ?,
                descripcion = ?,
                precio = ?
            WHERE id_plato = ?
        ");

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $id_plato
        ]);

        return $stmt->rowCount() > 0;
    }

    public function cambiarEstado(
        int $id_plato,
        bool $activo
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE plato
            SET activo = ?
            WHERE id_plato = ?
        ");

        $stmt->execute([
            $activo,
            $id_plato
        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminarPlato(
        int $id_plato
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM plato
            WHERE id_plato = ?
        ");

        $stmt->execute([$id_plato]);

        return $stmt->rowCount() > 0;
    }

    public function contarPlatos(): int {
        $stmt = $this->db->query("
            SELECT COUNT(*) total
            FROM plato
        ");

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}