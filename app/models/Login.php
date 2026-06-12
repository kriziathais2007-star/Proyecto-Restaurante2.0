<?php

require_once __DIR__ . '/../core/Database.php';

class Login {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function login(string $nombreUsuario, string $clave): array|false {

        $sql = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombreUsuario]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $clave === $usuario['clave']) {
            return $usuario;
        }

        return false;
    }
}