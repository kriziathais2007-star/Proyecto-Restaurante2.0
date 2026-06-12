<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Plato.php';

class PlatosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Plato();
        $this->view('platos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'platos'  => $modelo->obtenerPlatos()
        ]);
    }

    public function guardar(): void {
        $nombre      = $_POST['nombre']      ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio      = floatval($_POST['precio'] ?? 0);

        header('Content-Type: application/json');

        if (empty($nombre) || $precio <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Nombre y precio son obligatorios.']);
            return;
        }

        $model = new Plato();
        $resultado = $model->guardarPlato([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'precio'      => $precio,
            'activo'      => 1
        ]);

        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar el plato.']
        );
    }

    public function editar_plato(): void {
        $nombre      = $_POST['nombre']      ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio      = floatval($_POST['precio'] ?? 0);
        $activo      = isset($_POST['activo']) ? (bool)$_POST['activo'] : true;
        $id_plato    = (int)($_POST['id_plato'] ?? 0);

        $model     = new Plato();
        $resultado = $model->editarPlato($nombre, $descripcion, $precio, $activo, $id_plato);

        header('Content-Type: application/json');
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se realizaron cambios.']
        );
    }

    public function cambiar_estado(): void {
        $id_plato = (int)($_POST['id_plato'] ?? 0);
        $activo   = (bool)($_POST['activo'] ?? 0);

        $model     = new Plato();
        $resultado = $model->cambiarEstadoPlato($id_plato, $activo);

        header('Content-Type: application/json');
        echo json_encode(['ok' => $resultado]);
    }

    public function eliminar_plato(): void {
        $id_plato  = $_POST['id_plato'] ?? '';
        $model     = new Plato();
        $resultado = $model->eliminarPorIdPlato($id_plato);

        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }
}