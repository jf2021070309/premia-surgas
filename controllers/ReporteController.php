<?php

require_once __DIR__ . '/../models/ReporteModel.php';

class ReporteController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void {
        $model = new ReporteModel();
        
        $data = [
            'resumen'          => $model->getResumenGeneral(),
            'ventasConductor'  => $model->getVentasPorConductor(),
            'canjesRecientes'  => $model->getCanjesRecientes(),
            'premiosPopulares' => $model->getPremiosPopulares(),
            'ventasGrafico'    => $model->getVentasUltimosDias(15)
        ];

        require_once __DIR__ . '/../views/reportes/index.php';
    }
}
