<?php
require_once __DIR__ . '/../core/Database.php';

Class Login{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    public function login(string $nombreUsuario, string $clave):array|false{
        $sql = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombreUsuario]);
        $usuario = $stmt->fetch();
        if($usuario && $clave === $usuario['clave']){ //sin hash
            return $usuario; //Login correcto
        }
        return false;
    }
}