<?php
require_once __DIR__ . '/../core/Database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerUsuarios(): array {
        $stmt = $this->db->prepare("SELECT * FROM usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPorIdUsuario(string $id_usuario): bool {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerUsuarioPorId(int $id_usuario): array|false {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editarUsuario(string $nombre, string $usuario, string $clave, string $rol, int $id_usuario): bool {
        $stmt = $this->db->prepare("UPDATE usuario SET nombre = ?, usuario = ?, clave = ?, rol = ? WHERE id_usuario = ?");
        $stmt->execute([$nombre, $usuario, $clave, $rol, $id_usuario]);
        return $stmt->rowCount() > 0;
    }

    public function guardarUsuario(array $datos): bool {
        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, usuario, clave, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$datos['nombre'], $datos['usuario'], $datos['clave'], $datos['rol']]);
        return $stmt->rowCount() > 0;
    }

    public function existeUsuario(string $usuario): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        return $stmt->fetchColumn() > 0;
    }
}