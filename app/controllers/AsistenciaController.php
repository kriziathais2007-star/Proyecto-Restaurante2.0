<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Asistencia.php';

class AsistenciaController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo = new Asistencia();
        $variable_asistencia = $modelo->obtenerAsistencias();

        $this->view('asistencia/reportes', [
            'usuario'    => $_SESSION['usuario'],
            'asistencia' => $variable_asistencia
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }

    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        $modelo   = new Asistencia();
        $usuarios = $modelo->obtenerUsuarios();

        $this->view('asistencia/registro', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $usuarios
        ]);
    }

    public function registrar(): void {
        $this->registro();
    }

    public function guardar(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/asistencia/registro");
            exit();
        }

        $fecha        = trim($_POST['fecha']        ?? '');
        $hora_entrada = trim($_POST['hora_entrada'] ?? '');
        $hora_salida  = trim($_POST['hora_salida']  ?? '') ?: null;
        $estado       = trim($_POST['estado']       ?? '');
        $Id_Usuario   = (int)($_POST['Id_Usuario']  ?? 0);

        $modelo   = new Asistencia();
        $usuarios = $modelo->obtenerUsuarios();

        if (!$fecha || !$hora_entrada || !$estado || !$Id_Usuario) {
            $this->view('asistencia/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'error'    => 'Por favor completa todos los campos obligatorios.'
            ]);
            return;
        }

        $guardado = $modelo->crearAsistencia($fecha, $hora_entrada, $hora_salida, $estado, $Id_Usuario);

        if ($guardado) {
            header("Location: " . BASE_URL . "/asistencia/reportes");
            exit();
        } else {
            $this->view('asistencia/registro', [
                'usuario'  => $_SESSION['usuario'],
                'usuarios' => $usuarios,
                'error'    => 'Ocurrió un error al guardar. Intenta de nuevo.'
            ]);
        }
    }
}