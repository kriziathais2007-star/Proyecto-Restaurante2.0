<?php 
//Llamamos a la conexión de la base de datos.
require_once __DIR__ . '/../core/Database.php';
//Creamos el modelo o clase llamada Usuario (SINGULAR).
class Usuario{
    // La propiedad $db guardará la conexión PDO.
    // Le decimos que solo puede ser de tipo PDO (tipado estricto).
    // modificador de acceso("private") significa que solo se puede usar dentro de esta clase.
    private PDO $db;

    //Al crear el modelo, obtenemos la conexion automaticamente.
    public function __construct(){
        // Database::getConnection() nos regresa la conexión PDO que creamos en core/Database.php.
        // Al guardarla en $this->db, cualquier método de esta clase puede usarla.
        $this->db = Database::getConnection();
    }
    //Creamos el modulo para llamar todo los datos de la tabla USUARIOS
    //public function getAll():array
    public function obtenerUsuarios():array {
        // variable $sql para almacenar
        $sql = "SELECT * FROM usuario"; 
        // statement = declaración
        $stmt = $this->db->prepare($sql);
        // Ejecutamos la declaración ($stmt)
        $stmt->execute();
        //Retornamos los datos
        return $stmt->fetchAll();
    }
}

