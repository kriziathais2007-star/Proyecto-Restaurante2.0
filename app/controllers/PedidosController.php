<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidosController extends Controller {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function verificarSesion(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function reportes(): void {
        $this->verificarSesion();
        $pedidoModel = new Pedido($this->db);

        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin'    => $_GET['fecha_fin'] ?? '',
            'tipo'         => $_GET['tipo'] ?? '',
            'estado'       => $_GET['estado'] ?? '',
        ];

        $pedidos = $pedidoModel->obtenerReporte($filtros);
        $resumen = $pedidoModel->obtenerResumen($filtros);

        $this->view('pedidos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'filtros' => $filtros,
            'pedidos' => $pedidos,
            'resumen' => $resumen,
        ]);
    }

    public function croquis(): void {
        $this->verificarSesion();
        $pedidoModel = new Pedido($this->db);
        $mesas = $pedidoModel->obtenerEstadoMesas(21);
        $this->view('pedidos/croquis', [
            'usuario' => $_SESSION['usuario'],
            'mesas'   => $mesas,
        ]);
    }

    public function llevar(): void {
        $this->verificarSesion();
        $pedidoModel = new Pedido($this->db);
        $pedidos = $pedidoModel->obtenerPedidosLlevarActivos();
        $this->view('pedidos/llevar', [
            'usuario' => $_SESSION['usuario'],
            'pedidos' => $pedidos,
        ]);
    }

    // /pedidos/crear/Mesa/5  o  /pedidos/crear/Llevar
    public function crear(string $tipo, $numeroMesa = null): void {
        $this->verificarSesion();
        $pedidoModel = new Pedido($this->db);

        $idUsuario  = $_SESSION['usuario']['id_usuario'] ?? null;
        $numeroMesa = ($tipo === 'Mesa' && $numeroMesa !== null) ? (int) $numeroMesa : null;

        $idPedido = $pedidoModel->crearPedido($idUsuario, $tipo, $numeroMesa);

        header('Location: ' . BASE_URL . '/pedidos/detalle/' . $idPedido);
        exit;
    }

    // /pedidos/detalle/{id}
    public function detalle($id_pedido): void {
        $this->verificarSesion();
        $pedidoModel = new Pedido($this->db);
        $id_pedido = (int) $id_pedido;

        $pedido = $pedidoModel->obtenerPorId($id_pedido);
        if (!$pedido) {
            header('Location: ' . BASE_URL . '/pedidos/croquis');
            exit;
        }

        $platos   = $this->db->query("SELECT * FROM plato WHERE activo = 1 ORDER BY nombre")->fetchAll();
        $entradas = $this->db->query("SELECT * FROM entrada WHERE activo = 1 ORDER BY nombre")->fetchAll();

        $items  = $pedidoModel->obtenerItemsPedido($id_pedido);
        $extras = $pedidoModel->obtenerExtrasPedido($id_pedido);

        $this->view('pedidos/detalle', [
            'usuario'  => $_SESSION['usuario'],
            'pedido'   => $pedido,
            'platos'   => $platos,
            'entradas' => $entradas,
            'items'    => $items,
            'extras'   => $extras,
        ]);
    }

// POST /pedidos/agregarPlato/{id_pedido}
public function agregarPlato($id_pedido): void {
    $pedidoModel = new Pedido($this->db);
    $id_pedido = (int) $id_pedido;

    $id_plato   = (int) ($_POST['id_plato'] ?? 0);
    $id_entrada = !empty($_POST['id_entrada']) ? (int) $_POST['id_entrada'] : null;
    $cantidad   = max(1, (int) ($_POST['cantidad'] ?? 1));

    $stmt = $this->db->prepare("SELECT precio FROM plato WHERE id_plato = :id");
    $stmt->execute([':id' => $id_plato]);
    $precio = (float) $stmt->fetchColumn();

    $pedidoModel->agregarItemPlato($id_pedido, $id_plato, $id_entrada, $cantidad, $precio);

    header('Location: ' . BASE_URL . '/pedidos/detalle/' . $id_pedido);
    exit;
}

// POST /pedidos/agregarExtra/{id_pedido}
public function agregarExtra($id_pedido): void {
    $pedidoModel = new Pedido($this->db);
    $id_pedido = (int) $id_pedido;

    $id_entrada = (int) ($_POST['id_entrada'] ?? 0);
    $cantidad   = max(1, (int) ($_POST['cantidad'] ?? 1));

    $stmt = $this->db->prepare("SELECT precio FROM entrada WHERE id_entrada = :id");
    $stmt->execute([':id' => $id_entrada]);
    $precio = (float) $stmt->fetchColumn();

    $pedidoModel->agregarEntradaExtra($id_pedido, $id_entrada, $cantidad, $precio);

    header('Location: ' . BASE_URL . '/pedidos/detalle/' . $id_pedido);
    exit;
}
// POST /pedidos/cambiarEstado/{id_pedido}
public function cambiarEstado($id_pedido): void {
    $pedidoModel = new Pedido($this->db);
    $id_pedido = (int) $id_pedido;

    $estadosValidos = ['Pendiente', 'Preparando', 'Entregado', 'Pagado', 'Cancelado'];
    $estado = $_POST['estado'] ?? '';

    if (!in_array($estado, $estadosValidos)) {
        $estado = 'Pendiente';
    }

    $pedidoModel->cambiarEstado($id_pedido, $estado);

    header('Location: ' . BASE_URL . '/pedidos/detalle/' . $id_pedido);
    exit;
}
// POST /pedidos/cambiar-estado/{id}  -> versión AJAX (para reportes)
public function cambiarEstadoAjax($id_pedido): void {
    header('Content-Type: application/json');
    $pedidoModel = new Pedido($this->db);
    $id_pedido = (int) $id_pedido;

    $data = json_decode(file_get_contents('php://input'), true);
    $estado = $data['estado'] ?? '';

    $pedido = $pedidoModel->obtenerPorId($id_pedido);

    if (!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }

    // Regla de negocio: solo se permite Pagado -> Preparando desde Reportes
    if ($pedido['estado'] === 'Pagado' && $estado === 'Preparando') {
        $pedidoModel->cambiarEstado($id_pedido, 'Preparando');
        echo json_encode(['success' => true, 'message' => 'Estado actualizado a Preparando']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cambio de estado no permitido']);
    }
    exit;
}
// POST /pedidos/eliminar/{id}
public function eliminar($id): void {
    header('Content-Type: application/json');

    $pedidoModel = new Pedido($this->db);
    $id = (int) $id;

    $pedido = $pedidoModel->obtenerPorId($id);

    if (!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }

    $ok = $pedidoModel->eliminarPedido($id); // ← era "eliminar", debe ser "eliminarPedido"

    echo json_encode(['success' => $ok]);
    exit;
}
// POST /pedidos/pagar/{id}
public function pagar($id): void {
    header('Content-Type: application/json');

    $pedidoModel = new Pedido($this->db);
    $id = (int) $id;

    $pedido = $pedidoModel->obtenerPorId($id);
    if (!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado']);
        exit;
    }

    $metodo    = $_POST['metodo'] ?? 'Efectivo';
    $idUsuario = $_SESSION['usuario']['id_usuario'] ?? null;
    $monto     = (float) $pedido['total'];

    // Guardar foto Yape si viene
    $fotoYape = null;
    if ($metodo === 'Yape' && !empty($_FILES['foto_yape']['tmp_name'])) {
        $ext      = pathinfo($_FILES['foto_yape']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'yape_' . $id . '_' . time() . '.' . $ext;
        $destino  = __DIR__ . '/../../public/uploads/comprobantes/' . $nombreArchivo;
        if (move_uploaded_file($_FILES['foto_yape']['tmp_name'], $destino)) {
            $fotoYape = $nombreArchivo;
        }
    }

    $ok = $pedidoModel->pagarPedido($id, $monto, $metodo, $idUsuario, $fotoYape);

    echo json_encode(['success' => $ok, 'message' => $ok ? 'Pago registrado' : 'Error al registrar pago']);
    exit;
}
}
