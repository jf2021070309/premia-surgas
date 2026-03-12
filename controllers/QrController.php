<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../config/config.php';

class QrController {

    /**
     * Genera y sirve la imagen QR del cliente.
     * GET /qr/generate?id=XX
     */
    public function generate(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $model = new ClienteModel();
        $c     = $model->findById($id);

        if (!$c) {
            http_response_code(404);
            exit('Cliente no encontrado');
        }

        $scanUrl = BASE_URL . 'scan?c=' . urlencode($c['codigo']) . '&t=' . urlencode($c['token']);

        // Usar Google Charts API (funcional sin dependencias PECL)
        $qrApi = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='
                 . urlencode($scanUrl)
                 . '&choe=UTF-8';

        header('Location: ' . $qrApi);
        exit;
    }
}
