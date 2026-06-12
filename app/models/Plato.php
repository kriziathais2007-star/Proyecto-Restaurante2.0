<?php
require_once __DIR__ . '/../core/Database.php';

class Plato {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPlatos(): array {
        $stmt = $this->db->prepare("SELECT * FROM plato");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatosActivos(): array {
        $stmt = $this->db->prepare("SELECT * FROM plato WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatoPorId(int $id_plato): array|false {
        $stmt = $this->db->prepare("SELECT * FROM plato WHERE id_plato = ?");
        $stmt->execute([$id_plato]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarPlato(array $datos): bool {
        $stmt = $this->db->prepare("INSERT INTO plato (nombre, descripcion, precio, activo) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'] ?? null,
            $datos['precio'],
            $datos['activo'] ?? 1
        ]);
        return $stmt->rowCount() > 0;
    }

    public function editarPlato(string $nombre, ?string $descripcion, float $precio, bool $activo, int $id_plato): bool {
        $stmt = $this->db->prepare("UPDATE plato SET nombre = ?, descripcion = ?, precio = ?, activo = ? WHERE id_plato = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $activo, $id_plato]);
        return $stmt->rowCount() > 0;
    }

    public function cambiarEstadoPlato(int $id_plato, bool $activo): bool {
        $stmt = $this->db->prepare("UPDATE plato SET activo = ? WHERE id_plato = ?");
        $stmt->execute([$activo, $id_plato]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdPlato(string $id_plato): bool {
        $stmt = $this->db->prepare("DELETE FROM plato WHERE id_plato = ?");
        $stmt->execute([$id_plato]);
        return $stmt->rowCount() > 0;
    }
}