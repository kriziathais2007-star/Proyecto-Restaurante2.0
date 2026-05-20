<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/DetallePedido.php';

class DashboardController extends Controller {

    public function index(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

        $modelo  = new DetallePedido();
        $detalles = $modelo->obtenerDetalles();

        $this->view('dashboard/index', [
            'usuario'  => $_SESSION['usuario'],
            'detalles' => $detalles
        ]);
    }
}