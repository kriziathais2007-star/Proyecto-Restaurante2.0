<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Entrada.php';

class EntradasController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Entrada();
        $this->view('entradas/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'entradas' => $modelo->obtenerEntradas()
        ]);
    }

    public function guardar(): void {
        $nombre = $_POST['nombre'] ?? '';
        $precio = floatval($_POST['precio'] ?? 0);

        header('Content-Type: application/json');

        if (empty($nombre) || $precio <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Nombre y precio son obligatorios.']);
            return;
        }

        $model = new Entrada();
        $resultado = $model->guardarEntrada([
            'nombre' => $nombre,
            'precio' => $precio,
            'activo' => 1
        ]);

        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar la entrada.']
        );
    }

    public function editar_entrada(): void {
        $nombre     = $_POST['nombre']     ?? '';
        $precio     = floatval($_POST['precio'] ?? 0);
        $activo     = isset($_POST['activo']) ? (bool)$_POST['activo'] : true;
        $id_entrada = (int)($_POST['id_entrada'] ?? 0);

        $model     = new Entrada();
        $resultado = $model->editarEntrada($nombre, $precio, $activo, $id_entrada);

        header('Content-Type: application/json');
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se realizaron cambios.']
        );
    }

    public function cambiar_estado(): void {
        $id_entrada = (int)($_POST['id_entrada'] ?? 0);
        $activo     = (bool)($_POST['activo'] ?? 0);

        $model     = new Entrada();
        $resultado = $model->cambiarEstadoEntrada($id_entrada, $activo);

        header('Content-Type: application/json');
        echo json_encode(['ok' => $resultado]);
    }

    public function eliminar_entrada(): void {
        $id_entrada = $_POST['id_entrada'] ?? '';
        $model      = new Entrada();
        $resultado  = $model->eliminarPorIdEntrada($id_entrada);

        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }
}