<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Usuario();
        $this->view('usuarios/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $modelo->obtenerUsuarios()
        ]);
    }

    public function eliminar_usuario(): void {
        $id_usuario = $_POST['id_usuario'] ?? '';
        $model      = new Usuario();
        $resultado  = $model->eliminarPorIdUsuario($id_usuario);
        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }

    public function editar_usuario(): void {
        $nombre     = $_POST['nombre']     ?? '';
        $usuario    = $_POST['usuario']    ?? '';
        $clave      = $_POST['clave']      ?? '';
        $rol        = $_POST['rol']        ?? '';
        $id_usuario = (int)($_POST['id_usuario'] ?? 0);

        $model     = new Usuario();
        $resultado = $model->editarUsuario($nombre, $usuario, $clave, $rol, $id_usuario);

        header('Content-Type: application/json');
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se realizaron cambios.']
        );
    }

    public function guardar(): void {
        $nombre  = $_POST['nombre']  ?? '';
        $usuario = $_POST['usuario'] ?? '';
        $clave   = $_POST['clave']   ?? '';
        $rol     = $_POST['rol']     ?? '';

        $model = new Usuario();

        header('Content-Type: application/json');

        if ($model->existeUsuario($usuario)) {
            echo json_encode(['ok' => false, 'mensaje' => 'El usuario ya existe.']);
            return;
        }

        $resultado = $model->guardarUsuario(compact('nombre', 'usuario', 'clave', 'rol'));
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar.']
        );
    }
}