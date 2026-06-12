<?php

require_once __DIR__ . '/../core/Database.php';

class Asistencia {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerAsistencias(): array {

        $stmt = $this->db->prepare("
            SELECT a.*, u.nombre
            FROM asistencia a
            LEFT JOIN usuario u
                ON a.id_usuario = u.id_usuario
            ORDER BY a.fecha DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAsistenciaPorId(int $id_asistencia): array|false {

        $stmt = $this->db->prepare("
            SELECT a.*, u.nombre
            FROM asistencia a
            LEFT JOIN usuario u
                ON a.id_usuario = u.id_usuario
            WHERE a.id_asistencia = ?
        ");

        $stmt->execute([$id_asistencia]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerAsistenciasPorUsuario(int $id_usuario): array {

        $stmt = $this->db->prepare("
            SELECT *
            FROM asistencia
            WHERE id_usuario = ?
            ORDER BY fecha DESC
        ");

        $stmt->execute([$id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAsistenciasPorFecha(string $fecha): array {

        $stmt = $this->db->prepare("
            SELECT a.*, u.nombre
            FROM asistencia a
            LEFT JOIN usuario u
                ON a.id_usuario = u.id_usuario
            WHERE a.fecha = ?
        ");

        $stmt->execute([$fecha]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarEntrada(
        int $id_usuario,
        string $fecha,
        string $hora_entrada
    ): bool {

        $stmt = $this->db->prepare("
            INSERT INTO asistencia (
                id_usuario,
                fecha,
                hora_entrada
            )
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $id_usuario,
            $fecha,
            $hora_entrada
        ]);
    }

    public function registrarSalida(
        int $id_usuario,
        string $fecha,
        string $hora_salida
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE asistencia
            SET hora_salida = ?
            WHERE id_usuario = ?
            AND fecha = ?
            AND hora_salida IS NULL
        ");

        return $stmt->execute([
            $hora_salida,
            $id_usuario,
            $fecha
        ]);
    }

    public function guardarAsistencia(array $datos): bool {

        $stmt = $this->db->prepare("
            INSERT INTO asistencia (
                id_usuario,
                fecha,
                hora_entrada,
                hora_salida
            )
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $datos['id_usuario'],
            $datos['fecha'],
            $datos['hora_entrada'] ?? null,
            $datos['hora_salida'] ?? null
        ]);
    }

    public function editarAsistencia(
        string $fecha,
        ?string $hora_entrada,
        ?string $hora_salida,
        int $id_asistencia
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE asistencia
            SET fecha = ?,
                hora_entrada = ?,
                hora_salida = ?
            WHERE id_asistencia = ?
        ");

        return $stmt->execute([
            $fecha,
            $hora_entrada,
            $hora_salida,
            $id_asistencia
        ]);
    }

    public function eliminarAsistencia(int $id_asistencia): bool {

        $stmt = $this->db->prepare("
            DELETE FROM asistencia
            WHERE id_asistencia = ?
        ");

        return $stmt->execute([$id_asistencia]);
    }

    public function contarAsistencias(): int {

        $stmt = $this->db->query("
            SELECT COUNT(*) AS total
            FROM asistencia
        ");

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado['total'];
    }
}