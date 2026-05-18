<?php
require_once __DIR__ . '/../core/Controller.php';

// Controlador de la página principal (landing page).
class HomeController extends Controller {

    // Método por defecto. Se ejecuta cuando el usuario entra a la raíz del proyecto.
    public function index(): void {
        $this->view('home/landing');
    }
}
