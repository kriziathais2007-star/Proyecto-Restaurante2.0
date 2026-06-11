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
        $this->soloAdmin();

        $modelo = new Usuario();
        $variable_usuarios = $modelo->obtenerUsuarios();

        $this->view('usuarios/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $variable_usuarios
        ]);
    }
}