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
        $this->view('pedidos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'pedidos' => $modelo->obtenerPedidos()
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }

    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        $modelo = new Pedido();
        $this->view('pedidos/registro', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $modelo->obtenerUsuarios(),
            'platos'   => $modelo->obtenerPlatos(),
        ]);
    }

    public function registrar(): void {
        $this->registro();
    }

    public function guardar(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/pedidos/registro");
            exit();
        }

        $mesa          = (int)($_POST['mesa']          ?? 0);
        $estado_pedido = trim($_POST['estado_pedido']  ?? '');
        $Id_Usuario    = (int)($_POST['Id_Usuario']    ?? 0);
        $platos        = $_POST['platos']              ?? [];

        $modelo     = new Pedido();
        $usuarios   = $modelo->obtenerUsuarios();
        $platosDisp = $modelo->obtenerPlatos();

        $platosValidos = array_filter($platos, fn($p) => !empty($p['cantidad']) && (int)$p['cantidad'] > 0);

        if (!$mesa || !$estado_pedido || !$Id_Usuario || empty($platosValidos)) {
            $this->view('pedidos/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'platos'   => $platosDisp,
                'error'    => 'Completa todos los campos y selecciona al menos un plato.'
            ]);
            return;
        }

        $guardado = $modelo->crearPedidoConPlatos($mesa, $estado_pedido, $Id_Usuario, $platosValidos);

        if ($guardado) {
            header("Location: " . BASE_URL . "/pedidos/reportes");
            exit();
        } else {
            $this->view('pedidos/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'platos'   => $platosDisp,
                'error'    => 'Error al guardar. Intenta de nuevo.'
            ]);
        }
    }

    public function cambiarEstado(): void {
        if (!isset($_SESSION['usuario'])) {
            http_response_code(403);
            exit();
        }
        $id     = (int)($_POST['Id_Pedido']    ?? 0);
        $estado = trim($_POST['estado_pedido'] ?? '');

        $modelo = new Pedido();
        $modelo->cambiarEstado($id, $estado);

        header("Location: " . BASE_URL . "/pedidos/reportes");
        exit();
    }
}