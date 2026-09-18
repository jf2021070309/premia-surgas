<?php
/**
 * Script para ser ejecutado vía Cronjob (CLI) o llamado vía Web.
 * Se encarga de generar el reporte diario y enviarlo por correo.
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/ReporteModel.php';
require_once __DIR__ . '/helpers/EmailService.php';

$fecha = date('Y-m-d');
$model = new ReporteModel();
$registros = $model->getReporteDiarioConductores($fecha);

$agrupadoPorConductor = [];
$totalPuntosDia = 0;

foreach ($registros as $row) {
    $conductor = $row['conductor_nombre'] ?: 'Desconocido';
    if (!isset($agrupadoPorConductor[$conductor])) {
        $agrupadoPorConductor[$conductor] = [];
    }
    
    // Aquí NO convertimos a BASE_URL en PHP, porque la vista diario_conductores.php
    // asume que evidencia_foto es relativa y le hace BASE_URL . $v['evidencia_foto'].
    // Así que lo dejamos tal cual.
    
    $agrupadoPorConductor[$conductor][] = $row;
    $totalPuntosDia += (int)$row['puntos'];
}

// Generar el HTML del reporte
ob_start();
// Ocultamos los botones de la barra de acciones para que no salgan en el correo
?>
<style> .action-bar { display: none !important; } </style>
<?php
require __DIR__ . '/views/reportes/diario_conductores.php';
$html = ob_get_clean();

// Enviar correo
$destinatario = 'jaimeelias.tacna.2016@gmail.com';
$resultado = EmailService::enviarReporteDiario($html, $fecha, $destinatario);

if (php_sapi_name() === 'cli') {
    echo "[" . date('Y-m-d H:i:s') . "] " . $resultado['message'] . "\n";
} else {
    echo json_encode($resultado);
}
