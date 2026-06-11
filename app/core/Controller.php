<?php

class Controller {

    protected function soloAdmin(): void {
        if (($_SESSION['usuario']['rol'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    protected function soloRoles (array $roles): void {
        $rolActual = $_SESSION['usuario']['rol'] ?? '';
        if (!in_array($rolActual, $roles)) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    protected function view(string $vista, array $datos = []): void {
        extract($datos);
        require_once __DIR__ . '/../views/' . $vista . '.php';
    }
}