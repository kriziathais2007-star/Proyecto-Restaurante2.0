<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Pedido();
        $variable_pedidos = $modelo->obtenerPedidos();

        $this->view('pedidos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'pedidos' => $variable_pedidos
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }

    // ✅ registro() actualizado con usuarios para el select
    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo   = new Pedido();
        $usuarios = $modelo->obtenerUsuarios();

        $this->view('pedidos/registro', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $usuarios
        ]);
    }

    public function registrar(): void {
        $this->registro();
    }

    // ✅ guardar() procesa el formulario POST
    public function guardar(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/pedidos/registro");
            exit();
        }

        $mesa          = (int)($_POST['mesa']         ?? 0);
        $fecha         = trim($_POST['fecha']         ?? '');
        $hora_pedido   = trim($_POST['hora_pedido']   ?? '');
        $estado_pedido = trim($_POST['estado_pedido'] ?? '');
        $Id_Usuario    = (int)($_POST['Id_Usuario']   ?? 0);

        $modelo   = new Pedido();
        $usuarios = $modelo->obtenerUsuarios();

        if (!$mesa || !$fecha || !$hora_pedido || !$estado_pedido || !$Id_Usuario) {
            $this->view('pedidos/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'error'    => 'Por favor completa todos los campos.'
            ]);
            return;
        }

        $guardado = $modelo->crearPedido($mesa, $fecha, $hora_pedido, $estado_pedido, $Id_Usuario);

        if ($guardado) {
            header("Location: " . BASE_URL . "/pedidos/reportes");
            exit();
        } else {
            $this->view('pedidos/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'error'    => 'Ocurrió un error al guardar. Intenta de nuevo.'
            ]);
        }
    }
}