<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidosController {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ... aquí van tus métodos croquis() y llevar() ya existentes ...

    public function reportes(): void {
        $pedidoModel = new Pedido($this->db);

        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin'    => $_GET['fecha_fin'] ?? '',
            'tipo'         => $_GET['tipo'] ?? '',
            'estado'       => $_GET['estado'] ?? '',
        ];

        $pedidos = $pedidoModel->obtenerReporte($filtros);
        $resumen = $pedidoModel->obtenerResumen($filtros);

        require __DIR__ . '/../views/pedidos/reportes.php';
    }
}