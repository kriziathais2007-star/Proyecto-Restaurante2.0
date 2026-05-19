<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Plato.php';

// Controlador para el módulo de platos.
class PlatosController extends Controller {
    // index() es el método por defecto cuando la URL no especifica acción.
    // Ejemplo: /platos -> PlatosController::index()
    public function index(): void {
        // Reutilizamos la misma lógica que el reporte de platos.
        $this->reporte();
    }

    // reportes() muestra el listado completo de platos.
    // Ejemplo: /platos/reportes
    public function reporte(): void {
        // Validación de sesión: si no hay usuario logueado, redirigimos al login.
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        // Cargamos el modelo y obtenemos los datos de platos.
        $modelo = new Plato();
        $variable_platos = $modelo->obtenerPlatos();

        // Enviamos los datos a la vista.
        $this->view('platos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'platos' => $variable_platos
        ]);
    }

    // reportes() es un alias de reporte() para mayor claridad en la URL.
    // Explicación para alumnos: el Router convierte la URL /platos/reportes
    // en la llamada a PlatosController::reportes(). Al definir este alias
    // evitamos duplicar lógica y hacemos que la URL sea más legible.
    public function reportes(): void {
        // Reutilizamos la implementación de reporte() para mantener DRY.
        $this->reporte();
    }

    // registro() muestra el formulario para crear un nuevo plato.
    // Ejemplo: /platos/registro
    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $this->view('platos/registro', [
            'usuario' => $_SESSION['usuario']
        ]);
    }

    // registrar() es otro alias para la misma vista de registro.
    public function registrar(): void {
        $this->registro();
    }

}
