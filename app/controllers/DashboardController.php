<?php
require_once __DIR__ . '/../core/Controller.php';

// Controlador del panel principal del sistema (dashboard).
// Solo accesible para usuarios que hayan iniciado sesión.
class DashboardController extends Controller {

    // Método por defecto. Se ejecuta cuando el usuario entra a /dashboard
    public function index(): void {
        // Verificamos que el usuario haya iniciado sesión.
        // Si $_SESSION['usuario'] no existe, lo mandamos al login.
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Pasamos los datos del usuario a la vista para poder mostrarlos.
        $this->view('dashboard/index', [
            'usuario' => $_SESSION['usuario']
        ]);
    }
}