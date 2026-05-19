<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/DetallePedido.php';

// Controlador para el módulo de detalles de pedido.
class DetallePedidoController extends Controller {
    // index() es el método por defecto cuando la URL no especifica acción.
    // Ejemplo: /detalles-pedido -> DetallePedidoController::index()
    public function index(): void {
        // Reutilizamos la misma lógica que el reporte de detalles de pedido.
        $this->reporte();
    }

    // reportes() muestra el listado completo de detalles de pedido.
    // Ejemplo: /detalles-pedido/reportes
    public function reporte(): void {
        // Validación de sesión: si no hay usuario logueado, redirigimos al login.
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        // Cargamos el modelo y obtenemos los datos de detalles de pedido.
        $modelo = new DetallePedido();
        $variable_detalles = $modelo->obtenerDetallesPedido();

        // Enviamos los datos a la vista.
        $this->view('detalles-pedido/reportes', [
            'usuario' => $_SESSION['usuario'],
            'detalles' => $variable_detalles
        ]);
    }

    // reportes() es un alias de reporte() para mayor claridad en la URL.
    // Explicación para alumnos: el Router convierte la URL /detalles-pedido/reportes
    // en la llamada a DetallePedidoController::reportes(). Al definir este alias
    // evitamos duplicar lógica y hacemos que la URL sea más legible.
    public function reportes(): void {
        // Reutilizamos la implementación de reporte() para mantener DRY.
        $this->reporte();
    }

    // registro() muestra el formulario para crear un nuevo detalle de pedido.
    // Ejemplo: /detalles-pedido/registro
    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $this->view('detalles-pedido/registro', [
            'usuario' => $_SESSION['usuario']
        ]);
    }

    // registrar() es otro alias para la misma vista de registro.
    public function registrar(): void {
        $this->registro();
    }

}
