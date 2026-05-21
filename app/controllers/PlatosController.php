<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Plato.php';

class PlatosController extends Controller {

    public function index(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $modelo = new Plato();
        $this->view('platos/reportes', [
            'usuario' => $_SESSION['usuario'],
            'platos'  => $modelo->obtenerPlatos(),
        ]);
    }

    public function crear(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/platos');
            exit;
        }

        $nombre         = trim($_POST['nombre']         ?? '');
        $precio         = (float) ($_POST['precio']         ?? 0);
        $disponibilidad = (int)   ($_POST['disponibilidad']  ?? 1);
        $descripcion    = trim($_POST['descripcion']    ?? '');

        if (empty($nombre) || $precio <= 0) {
            header('Location: ' . BASE_URL . '/platos?error=datos_invalidos');
            exit;
        }

        $modelo = new Plato();
        $ok     = $modelo->crearPlato($nombre, $precio, $disponibilidad, $descripcion);

        if ($ok) {
            header('Location: ' . BASE_URL . '/platos?success=creado');
        } else {
            header('Location: ' . BASE_URL . '/platos?error=error_bd');
        }
        exit;
    }

    public function editar(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/platos');
            exit;
        }

        $id             = (int)   ($_POST['id']             ?? 0);
        $nombre         = trim($_POST['nombre']              ?? '');
        $precio         = (float) ($_POST['precio']          ?? 0);
        $disponibilidad = (int)   ($_POST['disponibilidad']  ?? 1);
        $descripcion    = trim($_POST['descripcion']         ?? '');

        if ($id <= 0 || empty($nombre) || $precio <= 0) {
            header('Location: ' . BASE_URL . '/platos?error=datos_invalidos');
            exit;
        }

        $modelo = new Plato();
        $ok     = $modelo->actualizarPlato($id, $nombre, $precio, $disponibilidad, $descripcion);

        if ($ok) {
            header('Location: ' . BASE_URL . '/platos?success=editado');
        } else {
            header('Location: ' . BASE_URL . '/platos?error=error_bd');
        }
        exit;
    }

    public function eliminar(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/platos');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/platos?error=datos_invalidos');
            exit;
        }

        $modelo = new Plato();
        $ok     = $modelo->eliminarPlato($id);

        if ($ok) {
            header('Location: ' . BASE_URL . '/platos?success=eliminado');
        } else {
            header('Location: ' . BASE_URL . '/platos?error=error_bd');
        }
        exit;
    }
}
?>