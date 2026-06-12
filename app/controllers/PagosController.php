<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pago.php';

class PagosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Pago();
        $this->view('pagos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'pagos'   => $modelo->obtenerPagos()
        ]);
    }

    // Devuelve los pagos asociados a un pedido (útil para mostrar en el detalle del pedido)
    public function por_pedido(): void {
        $id_pedido = (int)($_GET['id_pedido'] ?? 0);

        $modelo = new Pago();

        header('Content-Type: application/json');
        echo json_encode($modelo->obtenerPagosPorPedido($id_pedido));
    }

    public function guardar(): void {
        header('Content-Type: application/json');

        $rutaFoto = null;

        if (
            isset($_FILES['foto_yape']) &&
            $_FILES['foto_yape']['error'] === 0
        ) {
            $nombre = time() . "_" . basename($_FILES['foto_yape']['name']);

            $rutaDestino = __DIR__ . '/../../public/uploads/comprobantes/' . $nombre;

            move_uploaded_file(
                $_FILES['foto_yape']['tmp_name'],
                $rutaDestino
            );

            $rutaFoto = 'public/uploads/comprobantes/' . $nombre;
        }

        $datos = [
            'id_pedido'   => (int)($_POST['id_pedido'] ?? 0),
            'id_usuario'  => (int)($_SESSION['usuario']['id_usuario'] ?? 0),
            'monto'       => floatval($_POST['monto'] ?? 0),
            'metodo_pago' => trim($_POST['metodo_pago'] ?? ''),
            'foto_yape'   => $rutaFoto
        ];

        if (!$datos['id_pedido'] || $datos['monto'] <= 0 || empty($datos['metodo_pago'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'Pedido, monto y método de pago son obligatorios.']);
            return;
        }

        $modelo    = new Pago();
        $resultado = $modelo->guardarPago($datos);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar el pago.']
        );
    }

    public function eliminar_pago(): void {
        $id_pago = $_POST['id_pago'] ?? '';

        $model     = new Pago();
        $resultado = $model->eliminarPorIdPago($id_pago);

        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }
}