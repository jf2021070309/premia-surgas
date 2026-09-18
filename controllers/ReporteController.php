<?php

require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

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
            'ventasGrafico'    => $model->getVentasUltimosDias(15),
            'canjesGrafico'    => $model->getCanjesUltimosDias(15)
        ];

        require_once __DIR__ . '/../views/reportes/index.php';
    }

    public function auditoria(): void {
        $model = new AuditoriaModel();
        $logs = $model->getAll(500); // Obtener los primeros para carga rápida
        require_once __DIR__ . '/../views/reportes/auditoria.php';
    }

    public function getAuditLogsJson(): void {
        header('Content-Type: application/json');
        $model = new AuditoriaModel();
        echo json_encode($model->getAll(500));
        exit;
    }

    public function diarioConductores(): void {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        $model = new ReporteModel();
        $registros = $model->getReporteDiarioConductores($fecha);
        
        // Agrupar los registros por nombre del conductor
        $agrupadoPorConductor = [];
        $totalPuntosDia = 0;
        
        foreach ($registros as $row) {
            $conductor = $row['conductor_nombre'] ?: 'Desconocido';
            if (!isset($agrupadoPorConductor[$conductor])) {
                $agrupadoPorConductor[$conductor] = [];
            }
            $agrupadoPorConductor[$conductor][] = $row;
            $totalPuntosDia += (int)$row['puntos'];
        }

        require_once __DIR__ . '/../views/reportes/diario_conductores.php';
    }
}
