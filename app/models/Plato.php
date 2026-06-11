<?php
require_once __DIR__ . '/../core/Database.php';

class Plato {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ===================== PLATOS =====================

    public function obtenerTodos(): array {
        $stmt = $this->db->prepare("SELECT * FROM plato ORDER BY id_plato ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarPlato(array $datos): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO plato (nombre, descripcion, precio, activo) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$datos['nombre'], $datos['descripcion'], $datos['precio'], $datos['activo']]);
        return $stmt->rowCount() > 0;
    }

    public function editarPlato(array $datos): bool {
        $stmt = $this->db->prepare(
            "UPDATE plato SET nombre = ?, descripcion = ?, precio = ?, activo = ? WHERE id_plato = ?"
        );
        $stmt->execute([$datos['nombre'], $datos['descripcion'], $datos['precio'], $datos['activo'], $datos['id_plato']]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPlato(int $id_plato): bool {
        $stmt = $this->db->prepare("DELETE FROM plato WHERE id_plato = ?");
        $stmt->execute([$id_plato]);
        return $stmt->rowCount() > 0;
    }

    // ===================== ENTRADAS =====================

    public function obtenerEntradas(): array {
        $stmt = $this->db->prepare("SELECT * FROM entrada ORDER BY id_entrada ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarEntrada(array $datos): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO entrada (nombre, precio, activo) VALUES (?, ?, ?)"
        );
        $stmt->execute([$datos['nombre'], $datos['precio'], $datos['activo']]);
        return $stmt->rowCount() > 0;
    }

    public function editarEntrada(array $datos): bool {
        $stmt = $this->db->prepare(
            "UPDATE entrada SET nombre = ?, precio = ?, activo = ? WHERE id_entrada = ?"
        );
        $stmt->execute([$datos['nombre'], $datos['precio'], $datos['activo'], $datos['id_entrada']]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarEntrada(int $id_entrada): bool {
        $stmt = $this->db->prepare("DELETE FROM entrada WHERE id_entrada = ?");
        $stmt->execute([$id_entrada]);
        return $stmt->rowCount() > 0;
    }
}