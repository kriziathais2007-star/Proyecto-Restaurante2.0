```php
<?php
require_once __DIR__ . '/../core/Database.php';

class Plato {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPlatos(): array {
        $sql = "SELECT * FROM plato ORDER BY Id_Plato DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crearPlato(string $nombre, float $precio, int $disponibilidad, string $descripcion): bool {
        $sql = "INSERT INTO plato (nombre, precio, disponibilidad, descripcion)
                VALUES (:nombre, :precio, :disponibilidad, :descripcion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'         => $nombre,
            ':precio'         => $precio,
            ':disponibilidad' => $disponibilidad,
            ':descripcion'    => $descripcion
        ]);
    }
}
?>