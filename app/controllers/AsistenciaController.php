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
        $this->view('asistencia/reportes', [
            'usuario'     => $_SESSION['usuario'],
            'asistencias' => $modelo->obtenerAsistencias()
        ]);
    }

    // Marca la hora de entrada del usuario logueado
    public function marcar_entrada(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'Sesión no válida.']);
            return;
        }

        $id_usuario = (int)$_SESSION['usuario']['id_usuario'];
        $fecha      = date('Y-m-d');
        $hora       = date('H:i:s');

        $modelo = new Asistencia();

        if ($modelo->obtenerAsistenciaAbierta($id_usuario, $fecha)) {
            echo json_encode(['ok' => false, 'mensaje' => 'Ya registraste tu entrada hoy.']);
            return;
        }

        $resultado = $modelo->registrarEntrada($id_usuario, $fecha, $hora);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar la entrada.']
        );
    }

    // Marca la hora de salida del usuario logueado
    public function marcar_salida(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'Sesión no válida.']);
            return;
        }

        $id_usuario = (int)$_SESSION['usuario']['id_usuario'];
        $fecha      = date('Y-m-d');
        $hora       = date('H:i:s');

        $modelo     = new Asistencia();
        $abierta    = $modelo->obtenerAsistenciaAbierta($id_usuario, $fecha);

        if (!$abierta) {
            echo json_encode(['ok' => false, 'mensaje' => 'No tienes una entrada registrada para marcar salida.']);
            return;
        }

        $resultado = $modelo->registrarSalida((int)$abierta['id_asistencia'], $hora);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar la salida.']
        );
    }

    public function por_usuario(): void {
        $id_usuario = (int)($_GET['id_usuario'] ?? 0);

        $modelo = new Asistencia();

        header('Content-Type: application/json');
        echo json_encode($modelo->obtenerAsistenciasPorUsuario($id_usuario));
    }

    public function eliminar_asistencia(): void {
        $id_asistencia = $_POST['id_asistencia'] ?? '';

        $model     = new Asistencia();
        $resultado = $model->eliminarPorIdAsistencia($id_asistencia);

        header('Content-Type: application/json');
        echo json_encode(['eliminar' => $resultado]);
    }
}