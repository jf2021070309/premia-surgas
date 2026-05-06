<?php
require_once __DIR__ . '/../config/Database.php';

class AfiliadoAnuncioModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByUsuarioId(int $usuarioId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM afiliado_anuncios WHERE usuario_id = ? LIMIT 1");
        $stmt->execute([$usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllActive(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM afiliado_anuncios WHERE estado = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function upsert(array $data): bool
    {
        $existing = $this->getByUsuarioId($data['usuario_id']);

        if ($existing) {
            $sql = "UPDATE afiliado_anuncios SET 
                    nombre_negocio = :nombre_negocio, 
                    imagen_negocio = :imagen_negocio, 
                    carta_pdf = :carta_pdf, 
                    ubicacion = :ubicacion, 
                    celular = :celular,
                    estado = :estado,
                    color_fondo = :color_fondo
                    WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "INSERT INTO afiliado_anuncios (usuario_id, nombre_negocio, imagen_negocio, carta_pdf, ubicacion, celular, estado, color_fondo) 
                    VALUES (:usuario_id, :nombre_negocio, :imagen_negocio, :carta_pdf, :ubicacion, :celular, :estado, :color_fondo)";
            $stmt = $this->db->prepare($sql);
        }

        return $stmt->execute([
            'usuario_id' => $data['usuario_id'],
            'nombre_negocio' => $data['nombre_negocio'],
            'imagen_negocio' => $data['imagen_negocio'],
            'carta_pdf' => $data['carta_pdf'],
            'ubicacion' => $data['ubicacion'],
            'celular' => $data['celular'] ?? null,
            'estado' => $data['estado'] ?? 1,
            'color_fondo' => $data['color_fondo'] ?? '#A7D8F5'
        ]);
    }
}
