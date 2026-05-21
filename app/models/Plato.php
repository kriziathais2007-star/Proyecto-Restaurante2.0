<?php
require_once __DIR__ . '/../core/Database.php';

class Plato {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPlatos(): array {
        $sql  = "SELECT * FROM plato ORDER BY Id_Plato ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crearPlato(string $nombre, float $precio, int $disponibilidad, string $descripcion): bool {
        $sql  = "INSERT INTO plato (nombre, precio, disponibilidad, descripcion)
                 VALUES (:nombre, :precio, :disponibilidad, :descripcion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'         => $nombre,
            ':precio'         => $precio,
            ':disponibilidad' => $disponibilidad,
            ':descripcion'    => $descripcion,
        ]);
    }

    public function obtenerPlatoPorId(int $id): array|false {
        $sql  = "SELECT * FROM plato WHERE Id_Plato = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizarPlato(int $id, string $nombre, float $precio, int $disponibilidad, string $descripcion): bool {
        $sql  = "UPDATE plato SET nombre = :nombre, precio = :precio,
                 disponibilidad = :disponibilidad, descripcion = :descripcion
                 WHERE Id_Plato = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':nombre'         => $nombre,
            ':precio'         => $precio,
            ':disponibilidad' => $disponibilidad,
            ':descripcion'    => $descripcion,
        ]);
    }

    public function eliminarPlato(int $id): bool {
        $sql  = "DELETE FROM plato WHERE Id_Plato = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>