<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/CanjeModel.php';

class PanelController {

    public function index(): void {
        $this->requireAuth();
        
        if ($_SESSION['rol'] === 'cliente' && isset($_SESSION['codigo_cliente'])) {
            header('Location: ' . BASE_URL . 'scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente']);
            exit;
        }
        $model   = new ClienteModel();
        $totales = [
            'clientes' => count($model->getAll()),
        ];

        $metricas_adicionales = [];
        $notificaciones_recargas = [];
        $notificaciones = [];
        
        if ($_SESSION['rol'] === 'admin') {
            $canjeModel = new CanjeModel();
            $notificaciones = $canjeModel->getRecientes(5);

            // Nuevas Recargas Pendientes
            $db = Database::getConnection();
            $notificaciones_recargas = $db->query("SELECT r.*, c.nombre as cliente_nombre FROM recargas r JOIN clientes c ON r.cliente_id = c.id WHERE r.estado = 'pendiente' ORDER BY r.fecha DESC LIMIT 5")->fetchAll();
            
            // Canjes realizados hoy
            $canjes_hoy = $db->query("SELECT COUNT(*) as total FROM canjes WHERE DATE(fecha) = CURDATE()")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Gráfico Barras: Top 10 Premios más canjeados
            $top_premios = $db->query("
                SELECT p.nombre, COUNT(cj.id) as total 
                FROM canjes cj 
                JOIN premios p ON cj.premio_id = p.id 
                GROUP BY p.id 
                ORDER BY total DESC 
                LIMIT 10
            ")->fetchAll(PDO::FETCH_ASSOC);

            // Rendimiento de TODOS los conductores (incluso con 0 puntos)
            $ranking_conductores = $db->query("
                SELECT u.id, u.nombre, COALESCE(SUM(v.puntos), 0) as total_puntos 
                FROM usuarios u
                LEFT JOIN ventas v ON v.conductor_id = u.id 
                WHERE u.rol = 'conductor'
                GROUP BY u.id 
                ORDER BY total_puntos DESC
            ")->fetchAll(PDO::FETCH_ASSOC);

            // Ranking de usuarios con más canjes (lista lateral)
            $ranking = $db->query("SELECT c.nombre, COUNT(cj.id) as total_canjes FROM canjes cj JOIN clientes c ON cj.cliente_id = c.id GROUP BY c.id ORDER BY total_canjes DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
            
            // Puntos entregados hoy (desde ventas)
            $puntos_hoy = $db->query("SELECT SUM(puntos) as total FROM ventas WHERE DATE(fecha) = CURDATE()")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $metricas_adicionales['canjes_hoy'] = $canjes_hoy;
            $metricas_adicionales['puntos_hoy'] = $puntos_hoy;
            $metricas_adicionales['top_premios'] = $top_premios;
            $metricas_adicionales['ranking'] = $ranking;
            $metricas_adicionales['ranking_conductores'] = $ranking_conductores;
        }

        $this->render('panel', [
            'totales'        => $totales,
            'notificaciones' => $notificaciones,
            'notificaciones_recargas' => $notificaciones_recargas,
            'metricas_adicionales' => $metricas_adicionales
        ]);
    }

    public function conductorHistory(): void {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false]);
            exit;
        }

        $db = Database::getConnection();
        // Obtener historial de puntos del conductor en los últimos 15 días (Barras por día + sumatoria acumulada)
        $history = $db->prepare("
            SELECT DATE(fecha) as fecha, SUM(puntos) as total 
            FROM ventas 
            WHERE conductor_id = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            GROUP BY DATE(fecha)
            ORDER BY DATE(fecha) ASC
        ");
        $history->execute([$id]);
        $rows = $history->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $rows]);
        exit;
    }

    // ── helpers ──────────────────────────────────────────────────

    public function liveNotifications(): void {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false]);
            exit;
        }

        $db = Database::getConnection();
        
        // Pendientes de recargas
        $stmt_recargas = $db->prepare("SELECT r.id, r.puntos, r.monto, c.nombre as cliente_nombre, r.fecha FROM recargas r JOIN clientes c ON r.cliente_id = c.id WHERE r.estado = 'pendiente' ORDER BY r.id DESC");
        $stmt_recargas->execute();
        $recargas = $stmt_recargas->fetchAll(PDO::FETCH_ASSOC);

        // Pendientes de canjes
        $stmt_canjes = $db->prepare("SELECT cj.id, p.nombre as premio_nombre, cl.nombre as cliente_nombre, cj.fecha FROM canjes cj JOIN premios p ON cj.premio_id = p.id JOIN clientes cl ON cj.cliente_id = cl.id WHERE cj.estado = 'pendiente' ORDER BY cj.id DESC");
        $stmt_canjes->execute();
        $canjes = $stmt_canjes->fetchAll(PDO::FETCH_ASSOC);

        if (ob_get_length()) ob_clean();
        echo json_encode([
            'success'  => true,
            'recargas' => $recargas,
            'canjes'   => $canjes,
            'total'    => count($recargas) // Solo recargas para no confundir
        ]);
        exit;
    }


    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
