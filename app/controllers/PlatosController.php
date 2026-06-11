<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Plato.php';

class PlatosController extends Controller {

    public function index(): void {
        $this->reportes();
    }

    public function reportes(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        $this->soloAdmin();

        $modelo = new Plato();
        $this->view('platos/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'platos'   => $modelo->obtenerTodos(),
            'entradas' => $modelo->obtenerEntradas(),
        ]);
    }

    // ===================== PLATOS =====================

    public function guardar(): void {
        header('Content-Type: application/json');
        $datos = [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio'      => floatval($_POST['precio']  ?? 0),
            'activo'      => isset($_POST['activo']) ? (int)$_POST['activo'] : 1,
        ];
        if (empty($datos['nombre']) || $datos['precio'] <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Nombre y precio son obligatorios.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->guardarPlato($datos);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar el plato.']
        );
    }

    public function editar(): void {
        header('Content-Type: application/json');
        $datos = [
            'id_plato'    => (int)($_POST['id_plato']      ?? 0),
            'nombre'      => trim($_POST['nombre']          ?? ''),
            'descripcion' => trim($_POST['descripcion']     ?? ''),
            'precio'      => floatval($_POST['precio']      ?? 0),
            'activo'      => isset($_POST['activo']) ? (int)$_POST['activo'] : 1,
        ];
        if (!$datos['id_plato'] || empty($datos['nombre']) || $datos['precio'] <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->editarPlato($datos);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se realizaron cambios.']
        );
    }

    public function eliminar(): void {
        header('Content-Type: application/json');
        $id_plato = (int)($_POST['id_plato'] ?? 0);
        if (!$id_plato) {
            echo json_encode(['eliminar' => false, 'mensaje' => 'ID inválido.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->eliminarPlato($id_plato);
        echo json_encode(['eliminar' => $resultado]);
    }

    // ===================== ENTRADAS =====================

    public function guardar_entrada(): void {
        header('Content-Type: application/json');
        $datos = [
            'nombre' => trim($_POST['nombre']     ?? ''),
            'precio' => floatval($_POST['precio'] ?? 0),
            'activo' => isset($_POST['activo']) ? (int)$_POST['activo'] : 1,
        ];
        if (empty($datos['nombre']) || $datos['precio'] <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Nombre y precio son obligatorios.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->guardarEntrada($datos);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se pudo registrar la entrada.']
        );
    }

    public function editar_entrada(): void {
        header('Content-Type: application/json');
        $datos = [
            'id_entrada' => (int)($_POST['id_entrada'] ?? 0),
            'nombre'     => trim($_POST['nombre']      ?? ''),
            'precio'     => floatval($_POST['precio']  ?? 0),
            'activo'     => isset($_POST['activo']) ? (int)$_POST['activo'] : 1,
        ];
        if (!$datos['id_entrada'] || empty($datos['nombre']) || $datos['precio'] <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->editarEntrada($datos);
        echo json_encode($resultado
            ? ['ok' => true]
            : ['ok' => false, 'mensaje' => 'No se realizaron cambios.']
        );
    }

    public function eliminar_entrada(): void {
        header('Content-Type: application/json');
        $id_entrada = (int)($_POST['id_entrada'] ?? 0);
        if (!$id_entrada) {
            echo json_encode(['eliminar' => false, 'mensaje' => 'ID inválido.']);
            return;
        }
        $modelo    = new Plato();
        $resultado = $modelo->eliminarEntrada($id_entrada);
        echo json_encode(['eliminar' => $resultado]);
    }
}