<?php
require_once __DIR__ . '/../core/Database.php';

class Asistencia {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerAsistencias(): array {
        $sql = "SELECT a.*, u.nombre AS nombre_usuario
                FROM asistencia a
                INNER JOIN usuario u ON a.Id_Usuario = u.Id_Usuario
                ORDER BY a.id_asistencia DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerUsuarios(): array {
        $sql = "SELECT Id_Usuario, nombre FROM usuario ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crearAsistencia(string $fecha, string $hora_entrada, string $hora_salida, string $estado, int $Id_Usuario): bool {
        $sql = "INSERT INTO asistencia (fecha, hora_entrada, hora_salida, estado, Id_Usuario)
                VALUES (:fecha, :hora_entrada, :hora_salida, :estado, :Id_Usuario)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':fecha'        => $fecha,
            ':hora_entrada' => $hora_entrada,
            ':hora_salida'  => $hora_salida,
            ':estado'       => $estado,
            ':Id_Usuario'   => $Id_Usuario,
        ]);
    }
}
?>