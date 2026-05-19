<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pedido.php';

// Controlador para el módulo de pedidos.
class PedidosController extends Controller {
    // index() es el método por defecto cuando la URL no especifica acción.
    // Ejemplo: /pedidos -> PedidosController::index()
    public function index(): void {
        // Reutilizamos la misma lógica que el reporte de pedidos.
        $this->reporte();
    }

    // reportes() muestra el listado completo de pedidos.
    // Ejemplo: /pedidos/reportes
    public function reporte(): void {
        // Validación de sesión: si no hay usuario logueado, redirigimos al login.
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        // Cargamos el modelo y obtenemos los datos de pedidos.
        $modelo = new Pedido();
        $variable_pedidos = $modelo->obtenerPedidos();

        // Enviamos los datos a la vista.
        $this->view('pedidos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'pedidos' => $variable_pedidos          
        ]);
    }

    // reportes() es un alias de reporte() para mayor claridad en la URL.
    // Explicación para alumnos: el Router convierte la URL /pedidos/reportes
    // en la llamada a PedidosController::reportes(). Al definir este alias
    // evitamos duplicar lógica y hacemos que la URL sea más legible.
    public function reportes(): void {
        // Reutilizamos la implementación de reporte() para mantener DRY.
        $this->reporte();
    }

    // registro() muestra el formulario para crear un nuevo pedido.
    // Ejemplo: /pedidos/registro
    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $this->view('pedidos/registro', [
            'usuario' => $_SESSION['usuario']
        ]);
    }

    // registrar() es otro alias para la misma vista de registro.
    public function registrar(): void {
        $this->registro();
    }

}
