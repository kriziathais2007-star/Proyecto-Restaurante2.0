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
            'usuario'  => $_SESSION['usuario'],
            'platos' => $modelo->obtenerPlatos(),
        ]);
    }
}
?>