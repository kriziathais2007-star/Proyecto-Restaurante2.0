<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/DetallePedido.php';
require_once __DIR__ . '/../models/DetalleEntradaExtra.php';
require_once __DIR__ . '/../models/Plato.php';
require_once __DIR__ . '/../models/Entrada.php';

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

    // Vista para crear un pedido de tipo "Mesa"
    public function mesa(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $platoModel   = new Plato();
        $entradaModel = new Entrada();

        $this->view('pedidos/mesa', [
            'usuario'  => $_SESSION['usuario'],
            'platos'   => $platoModel->obtenerPlatosActivos(),
            'entradas' => $entradaModel->obtenerEntradasActivas()
        ]);
    }

    // Muestra el detalle de un pedido (items + entradas extra)
    public function ver(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $id_pedido = (int)($_GET['id'] ?? 0);

        $pedidoModel  = new Pedido();
        $detalleModel = new DetallePedido();
        $extraModel   = new DetalleEntradaExtra();

        $this->view('pedidos/ver', [
            'usuario'  => $_SESSION['usuario'],
            'pedido'   => $pedidoModel->obtenerPedidoPorId($id_pedido),
            'detalles' => $detalleModel->obtenerDetallesPorPedido($id_pedido),
            'extras'   => $extraModel->obtenerDetallesExtraPorPedido($id_pedido)
        ]);
    }

    // Crea el pedido junto con sus items (platos/entradas) y entradas extra
    public function guardar(): void {
        header('Content-Type: application/json');

        $tipo        = $_POST['tipo']        ?? '';
        $numero_mesa = $_POST['numero_mesa'] ?? null;
        $id_usuario  = (int)($_SESSION['usuario']['id_usuario'] ?? 0);

        $items  = json_decode($_POST['items']  ?? '[]', true);
        $extras = json_decode($_POST['extras'] ?? '[]', true);

        if (empty($tipo) || (empty($items) && empty($extras))) {
            echo json_encode(['ok' => false, 'mensaje' => 'El pedido debe tener al menos un ítem.']);
            return;
        }

        $pedidoModel  = new Pedido();
        $detalleModel = new DetallePedido();
        $extraModel   = new DetalleEntradaExtra();

        $total = 0;
        foreach ($items as $item) {
            $total += (float)$item['cantidad'] * (float)$item['precio_unitario'];
        }
        foreach ($extras as $extra) {
            $total += (float)$extra['cantidad'] * (float)$extra['precio_unitario'];
        }

        $id_pedido = $pedidoModel->guardarPedido([
            'id_usuario'   => $id_usuario,
            'tipo'         => $tipo,
            'numero_mesa'  => $numero_mesa !== '' ? $numero_mesa : null,
            'total'        => $total,
            'estado'       => 'Pendiente'
        ]);

        if (!$id_pedido) {
            echo json_encode(['ok' => false, 'mensaje' => 'No se pudo registrar el pedido.']);
            return;
        }

        foreach ($items as $item) {
            $detalleModel->guardarDetalle([
                'id_pedido'       => $id_pedido,
                'id_plato'        => $item['id_plato']   ?? null,
                'id_entrada'      => $item['id_entrada'] ?? null,
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal'        => $item['cantidad'] * $item['precio_unitario']
            ]);
        }

        foreach ($extras as $extra) {
            $extraModel->guardarDetalleExtra([
                'id_pedido'       => $id_pedido,
                'id_entrada'      => $extra['id_entrada'] ?? null,
                'cantidad'        => $extra['cantidad'],
                'precio_unitario' => $extra['precio_unitario'],
                'subtotal'        => $extra['cantidad'] * $extra['precio_unitario']
            ]);
        }

        echo json_encode(['ok' => true, 'id_pedido' => $id_pedido]);
    }

    public function actualizar_estado(): void {
        $id_pedido = (int)($_POST['id_pedido'] ?? 0);
        $estado    = $_POST['estado'] ?? '';

        $model     = new Pedido();
        $resultado = $model->actualizarEstado($id_pedido, $estado);

        header('Content-Type: application/json');
        echo json_encode(['ok' => $resultado]);
    }

    public function eliminar_pedido(): void {
        $id_pedido = $_POST['id_pedido'] ?? '';

        $detalleModel = new DetallePedido();
        $extraModel   = new DetalleEntradaExtra();
        $pedidoModel  = new Pedido();

        // Elimina primero los detalles relacionados (por las FK)
        $detalleModel->eliminarPorIdPedido((int)$id_pedido);
        $extraModel->eliminarPorIdPedido((int)$id_pedido);
        $resultado = $pedidoModel->eliminarPorIdPedido($id_pedido);

        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }
}