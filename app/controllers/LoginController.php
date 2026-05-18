<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Login.php';

// LoginController hereda de Controller para poder usar el método view().
class LoginController extends Controller {

    // El Router ejecuta "index" cuando la URL es solo "/login"
    public function index(): void {
        // Variable que guarda el error si las credenciales son incorrectas
        $error = null;

        // REQUEST_METHOD nos dice si el usuario llegó a la página (GET)
        // o si acaba de enviar el formulario (POST).
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // trim() quita los espacios al inicio y al final del texto.
            // ?? '' significa: si el campo no existe en $_POST, usa cadena vacía.
            $usuario = trim($_POST['user'] ?? '');
            $clave   = trim($_POST['pass'] ?? '');

            if (empty($usuario) || empty($clave)) {
                $error = "Completa todos los campos, por favor.";
            } else {
                // Llamamos al modelo y le pedimos que verifique las credenciales.
                // Si son correctas, $resultado tiene los datos del usuario.
                // Si son incorrectas, $resultado es false.
                $resultado = (new Login())->login($usuario, $clave);

                if ($resultado) {
                    // Guardamos los datos del usuario en la sesión.
                    // session_start() ya fue llamado en App.php, no hace falta repetirlo.
                    $_SESSION['usuario'] = $resultado;

                    // Login correcto: redirigimos al dashboard
                    header('Location: ' . BASE_URL . '/dashboard');
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        // Cargamos la vista del login pasándole la variable $error.
        // Si no hubo POST (GET normal), $error es null y no se muestra nada.
        $this->view('auth/login', ['error' => $error]);
    }
}
