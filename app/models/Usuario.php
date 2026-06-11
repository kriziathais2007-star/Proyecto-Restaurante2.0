<?php
require_once __DIR__ . '/../core/Database.php';
class Usuario{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }
    public function obtenerUsuarios():array{
        $sql = "SELECT * FROM usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}