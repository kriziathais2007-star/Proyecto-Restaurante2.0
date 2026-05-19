<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController extends Controller {

    public function index(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $modelo = new Usuario();
        $this->view('usuarios/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $modelo->obtenerUsuarios(),
        ]);
    }
}
?>