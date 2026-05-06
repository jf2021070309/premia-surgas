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

            // Donut: Canjes Full (monto=0) vs Mix (monto>0)
            $canjes_full = $db->query("SELECT COUNT(*) as total FROM canjes WHERE monto = 0 OR monto IS NULL")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            $canjes_mix  = $db->query("SELECT COUNT(*) as total FROM canjes WHERE monto > 0")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Line/Area: Puntos entregados por día (últimos 14 días)
            $puntos_por_dia = $db->query("
                SELECT DATE(fecha) as fecha, SUM(puntos) as total
                FROM ventas
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
                GROUP BY DATE(fecha)
                ORDER BY fecha ASC
            ")->fetchAll(PDO::FETCH_ASSOC);

            $metricas_adicionales['canjes_hoy']          = $canjes_hoy;
            $metricas_adicionales['puntos_hoy']          = $puntos_hoy;
            $metricas_adicionales['top_premios']         = $top_premios;
            $metricas_adicionales['ranking']             = $ranking;
            $metricas_adicionales['ranking_conductores'] = $ranking_conductores;
            $metricas_adicionales['canjes_full']         = (int)$canjes_full;
            $metricas_adicionales['canjes_mix']          = (int)$canjes_mix;
            $metricas_adicionales['puntos_por_dia']      = $puntos_por_dia;
        }

        if ($_SESSION['rol'] === 'conductor' || $_SESSION['rol'] === 'afiliado') {
            $db = Database::getConnection();
            $id_usuario = $_SESSION['id_usuario'];
            $rol_sesion = $_SESSION['rol'];

            // Puntos dados por este usuario HOY
            $puntos_hoy = $db->query("SELECT SUM(puntos) as total FROM ventas WHERE conductor_id = $id_usuario AND DATE(fecha) = CURDATE()")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total puntos HISTORICO
            $total_historico = $db->query("SELECT SUM(puntos) as total FROM ventas WHERE conductor_id = $id_usuario")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Total clientes atendidos por este usuario
            $total_clientes = $db->query("SELECT COUNT(DISTINCT cliente_id) as total FROM ventas WHERE conductor_id = $id_usuario")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Ranking posicion (por ahora comparados con conductores si es conductor, o general si es afiliado?)
            // Para simplificar, los comparamos en sus respectivos grupos o general
            $ranking_all = $db->query("SELECT u.id, COALESCE(SUM(v.puntos), 0) as total FROM usuarios u LEFT JOIN ventas v ON v.conductor_id = u.id WHERE u.rol = '$rol_sesion' GROUP BY u.id ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC);
            $mi_posicion = 0;
            $distancia_siguiente = 0;
            $total_lider = $ranking_all[0]['total'] ?? 0;

            foreach($ranking_all as $pos => $row) {
                if($row['id'] == $id_usuario) {
                    $mi_posicion = $pos + 1;
                    if ($pos > 0) {
                        $distancia_siguiente = $ranking_all[$pos-1]['total'] - $row['total'];
                    }
                    break;
                }
            }

            // Historial para su propio grafico (ultimos 7 dias)
            $mi_historial = $db->query("SELECT DATE(fecha) as fecha, SUM(puntos) as total FROM ventas WHERE conductor_id = $id_usuario AND fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(fecha) ORDER BY fecha ASC")->fetchAll(PDO::FETCH_ASSOC);

            // Ultimas 5 ventas realizadas
            $ultimas_ventas = $db->query("
                SELECT v.*, c.nombre as cliente_nombre 
                FROM ventas v 
                JOIN clientes c ON v.cliente_id = c.id 
                WHERE v.conductor_id = $id_usuario 
                ORDER BY v.fecha DESC 
                LIMIT 5
            ")->fetchAll(PDO::FETCH_ASSOC);

            $metricas_adicionales['puntos_hoy'] = $puntos_hoy;
            $metricas_adicionales['total_historico'] = $total_historico;
            $metricas_adicionales['total_clientes_propios'] = $total_clientes;
            $metricas_adicionales['mi_posicion'] = $mi_posicion;
            $metricas_adicionales['mi_historial'] = $mi_historial;
            $metricas_adicionales['ultimas_ventas'] = $ultimas_ventas;
            $metricas_adicionales['distancia_siguiente'] = $distancia_siguiente;
            $metricas_adicionales['total_lider'] = $total_lider;
        }

        $this->render('panel', [
            'totales'        => $totales,
            'notificaciones' => $notificaciones,
            'notificaciones_recargas' => $notificaciones_recargas,
            'metricas_adicionales' => $metricas_adicionales,
            'ventas_pendientes' => (new VentaModel())->getPendientes()
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
            WHERE conductor_id = ? AND estado = 'aprobado' AND fecha >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            GROUP BY DATE(fecha)
            ORDER BY DATE(fecha) ASC
        ");
        $history->execute([$id]);
        $rows = $history->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $rows]);
        exit;
    }

    public function validarVenta(): void {
        $this->requireAuth();
        if ($_SESSION['rol'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'No autorizado.']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id     = (int)($data['id'] ?? 0);
        $estado = $data['estado'] ?? ''; // 'aprobado' o 'rechazado'

        if (!$id || !in_array($estado, ['aprobado', 'rechazado'])) {
            $this->json(['success' => false, 'message' => 'Datos inválidos.']);
        }

        $ventaModel = new VentaModel();
        if ($ventaModel->validar($id, $estado, $_SESSION['id_usuario'])) {
            
            // AUDITORIA
            $v = $ventaModel->getById($id);
            $audit = new AuditoriaModel();
            require_once __DIR__ . '/../models/AuditoriaModel.php';
            $audit = new AuditoriaModel();
            $audit->registrar($_SESSION['id_usuario'], 'VALIDAR_PUNTOS_CONDUCTOR', "El administrador procesó como $estado la asignación de {$v['puntos']} puntos (ID: $id)", 'ADMINISTRACION');

            $this->json(['success' => true, 'message' => 'Movimiento actualizado correctamente.']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al actualizar el movimiento.']);
        }
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

        // Pendientes de ventas (Puntos de conductores)
        $stmt_ventas = $db->prepare("SELECT v.id, v.puntos, c.nombre as cliente_nombre, u.nombre as conductor_nombre, v.fecha FROM ventas v JOIN clientes c ON v.cliente_id = c.id JOIN usuarios u ON v.conductor_id = u.id WHERE v.estado = 'pendiente' ORDER BY v.id DESC");
        $stmt_ventas->execute();
        $ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

        if (ob_get_length()) ob_clean();
        echo json_encode([
            'success'  => true,
            'recargas' => $recargas,
            'canjes'   => $canjes,
            'ventas'   => $ventas,
            'total'    => count($recargas) + count($ventas) + count($canjes)
        ]);
        exit;
    }

    private function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
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
