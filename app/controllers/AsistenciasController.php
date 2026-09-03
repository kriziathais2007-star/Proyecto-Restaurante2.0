<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Asistencia.php';

class AsistenciasController extends Controller {

    private Asistencia $asistenciaModel;

    public function __construct() {
        $this->asistenciaModel = new Asistencia();
    }

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {

        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $asistencias = $this->asistenciaModel->obtenerAsistencias();

        $this->view('asistencias/reportes', [
            'usuario' => $_SESSION['usuario'],
            'asistencias' => $asistencias
        ]);
    }

    public function registrarEntrada(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
            return;
        }

        $id_usuario = $_SESSION['usuario']['id_usuario'];

        if ($this->asistenciaModel->yaRegistroEntradaHoy($id_usuario)) {
            echo json_encode(['success' => false, 'message' => 'Ya registraste tu entrada hoy.']);
            return;
        }

        $ok = $this->asistenciaModel->registrarEntrada($id_usuario, date('Y-m-d'), date('H:i:s'));

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Entrada registrada correctamente.' : 'Error al registrar entrada.'
        ]);
    }

    public function registrarSalida(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
            return;
        }

        $id_usuario = $_SESSION['usuario']['id_usuario'];

        if (!$this->asistenciaModel->yaRegistroEntradaHoy($id_usuario)) {
            echo json_encode(['success' => false, 'message' => 'Primero debes registrar tu entrada.']);
            return;
        }

        if ($this->asistenciaModel->yaRegistroSalidaHoy($id_usuario)) {
            echo json_encode(['success' => false, 'message' => 'Ya registraste tu salida hoy.']);
            return;
        }

        $ok = $this->asistenciaModel->registrarSalida($id_usuario, date('Y-m-d'), date('H:i:s'));

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Salida registrada correctamente.' : 'Error al registrar salida.'
        ]);
    }

    public function eliminar(): void {

        header('Content-Type: application/json');

        $id_asistencia = $_POST['id_asistencia'] ?? 0;

        $ok = $this->asistenciaModel->eliminarAsistencia((int)$id_asistencia);

        echo json_encode([
            'success' => $ok
        ]);
    }
}