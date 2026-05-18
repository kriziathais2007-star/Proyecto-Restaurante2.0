<?php
// Necesitamos cargar la clase Database antes de usarla.
// __DIR__ devuelve la carpeta donde está este archivo (app/models/).
// Con /../ subimos un nivel y entramos a core/Database.php.
require_once __DIR__ . '/../core/Database.php';

//Creamos una clase para el usuario - login
Class Login{
    // La propiedad $db guardará la conexión PDO.
    // Le decimos que solo puede ser de tipo PDO (tipado estricto).
    // "private" significa que solo se puede usar dentro de esta clase.
    private PDO $db;

    //Al crear el modelo, obtenemos la conexion automaticamente.
    public function __construct(){
        // Database::getConnection() nos regresa la conexión PDO que creamos en core/Database.php.
        // Al guardarla en $this->db, cualquier método de esta clase puede usarla.
        $this->db = Database::getConnection();
    }

    //Buscamos un usuario por su nombre y verificamos su contraseña.
    //devuelvo los datos del usuario si es correcto
    public function login(string $nombreUsuario, string $clave):array|false{
        // El signo ? en la consulta SQL es un parámetro preparado.
        // Nunca pongas el valor directo en el SQL (ej: "... = '$nombreUsuario'")
        // porque eso abre la puerta a ataques de SQL Injection.
        $sql = "SELECT * FROM usuario WHERE nombre_usuario = ?";

        //statement-declaración
        // prepare() le dice a la base de datos que prepare la consulta con el parámetro.
        $stmt = $this->db->prepare($sql);

        // execute() envía el valor real del parámetro de forma segura.
        $stmt->execute([$nombreUsuario]);

        // fetch() regresa la primera fila encontrada como array asociativo.
        // Si no encuentra nada, regresa false.
        $usuario = $stmt->fetch();

        //Verificamos que si exista el usuario y que la contraseña sea correcta
        //if($usuario && password_verify($clave,$usuario['clave'])){ //con hash
        if($usuario && $clave === $usuario['clave']){ //sin hash
            return $usuario; //Login correcto
        }

        // Si el usuario no existe o la contraseña es incorrecta, regresamos false.
        return false;
    }
}
