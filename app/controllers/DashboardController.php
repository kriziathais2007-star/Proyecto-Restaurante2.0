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
    header('Pragma: no-cache');

    $modelo = new DetallePedido();

    $this->view('dashboard/index', [
        'usuario'           => $_SESSION['usuario'],
        'detalles'          => $modelo->obtenerDetalles(),
        'pedidos_hoy'       => $modelo->contarPedidosHoy(),
        'ingresos_hoy'      => $modelo->ingresosTotalesHoy(),
        'pedidos_pendientes'=> $modelo->pedidosPendientes(),
        'pedidos_recientes' => $modelo->pedidosRecientes(5),
    ]);
}}