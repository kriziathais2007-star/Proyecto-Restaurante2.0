<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Login.php';

class LoginController extends Controller {

    public function index(): void {
        // Si ya tiene sesión activa, redirige directo al dashboard
        if (isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/dashboard');
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

                    // Esto saca al login del historial de navegación
                    header('Location: ' . BASE_URL . '/dashboard', true, 303);
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }
}