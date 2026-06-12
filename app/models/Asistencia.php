<?php
require_once __DIR__ . '/../core/Database.php';

class Asistencia {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerAsistencias(): array {
        $stmt = $this->db->prepare("SELECT * FROM asistencia ORDER BY fecha DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAsistenciaPorId(int $id_asistencia): array|false {
        $stmt = $this->db->prepare("SELECT * FROM asistencia WHERE id_asistencia = ?");
        $stmt->execute([$id_asistencia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerAsistenciasPorUsuario(int $id_usuario): array {
        $stmt = $this->db->prepare("SELECT * FROM asistencia WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca si el usuario ya tiene un registro de asistencia abierto (sin hora_salida) en una fecha
    public function obtenerAsistenciaAbierta(int $id_usuario, string $fecha): array|false {
        $stmt = $this->db->prepare("SELECT * FROM asistencia WHERE id_usuario = ? AND fecha = ? AND hora_salida IS NULL");
        $stmt->execute([$id_usuario, $fecha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarEntrada(int $id_usuario, string $fecha, string $hora_entrada): bool {
        $stmt = $this->db->prepare("INSERT INTO asistencia (id_usuario, fecha, hora_entrada) VALUES (?, ?, ?)");
        $stmt->execute([$id_usuario, $fecha, $hora_entrada]);
        return $stmt->rowCount() > 0;
    }

    public function registrarSalida(int $id_asistencia, string $hora_salida): bool {
        $stmt = $this->db->prepare("UPDATE asistencia SET hora_salida = ? WHERE id_asistencia = ?");
        $stmt->execute([$hora_salida, $id_asistencia]);
        return $stmt->rowCount() > 0;
    }

    public function eliminarPorIdAsistencia(string $id_asistencia): bool {
        $stmt = $this->db->prepare("DELETE FROM asistencia WHERE id_asistencia = ?");
        $stmt->execute([$id_asistencia]);
        return $stmt->rowCount() > 0;
    }
}