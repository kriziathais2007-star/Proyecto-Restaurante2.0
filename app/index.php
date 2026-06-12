<?php
// Punto de entrada de la aplicación.
// Todo pasa por aquí gracias al .htaccess.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargamos la configuración (constantes de BD, BASE_URL, etc.)
require_once __DIR__ . '/config/config.php';

// Cargamos la clase App que arranca el Router
require_once __DIR__ . '/core/App.php';

// Iniciamos la aplicación
(new App())->run();
