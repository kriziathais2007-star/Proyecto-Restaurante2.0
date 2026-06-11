<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Login.php';

class LoginController extends Controller {

    public function index(): void {
        if (isset($_SESSION['usuario'])) {
            $destino = $this->destinoPorRol($_SESSION['usuario']['rol']);
            header('Location: ' . BASE_URL . $destino);
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['user'] ?? '');
            $clave   = trim($_POST['pass'] ?? '');

            if (empty($usuario) || empty($clave)) {
                $error = "Completa todos los campos, por favor.";
            } else {
                $resultado = (new Login())->login($usuario, $clave);

                if ($resultado) {
                    $_SESSION['usuario'] = $resultado;
                    $destino = $this->destinoPorRol($resultado['rol']);
                    header('Location: ' . BASE_URL . $destino);
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    // ✅ NUEVO - centraliza la redirección por rol
    private function destinoPorRol(string $rol): string {
        return match($rol) {
            'admin'    => '/dashboard',
            'mesero'   => '/dashboard',
            'cocinero' => '/dashboard',
            default    => '/dashboard'
        };
    }
}