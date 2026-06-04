const express = require('express');
const router = express.Router();
const pool = require('../config/db');
const requireApiKey = require('../middleware/apiKey');
const upload = require('../middleware/upload');
const { uploadToImgBB } = require('../helpers/uploadHelper');

/**
 * @swagger
 * /api/puntos:
 *   get:
 *     summary: Obtener todos los puntos de venta
 *     description: Retorna una lista con todos los puntos de venta registrados.
 *     tags: [Puntos de Venta]
 *     responses:
 *       200:
 *         description: Lista de puntos de venta
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 ok:
 *                   type: boolean
 *                   example: true
 *                 data:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/PuntoVenta'
 *                 message:
 *                   type: string
 *                   example: Puntos de venta obtenidos correctamente
 *       500:
 *         description: Error del servidor
 */
router.get('/', async (req, res) => {
    try {
        const { rows } = await pool.query('SELECT * FROM puntos_venta ORDER BY created_at DESC');
        return res.json({
            ok: true,
            data: rows,
            message: 'Puntos de venta obtenidos correctamente'
        });
    } catch (error) {
        console.error('Error en GET /api/puntos:', error);
        return res.status(500).json({
            ok: false,
            data: null,
            message: 'Error al obtener los puntos de venta: ' + error.message
        });
    }
});

/**
 * @swagger
 * /api/puntos/{id}:
 *   get:
 *     summary: Obtener punto de venta por ID
 *     description: Retorna los detalles de un punto de venta específico.
 *     tags: [Puntos de Venta]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         schema:
 *           type: integer
 *         description: ID del punto de venta
 *     responses:
 *       200:
 *         description: Punto de venta encontrado
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 ok:
 *                   type: boolean
 *                   example: true
 *                 data:
 *                   $ref: '#/components/schemas/PuntoVenta'
 *       404:
 *         description: Punto de venta no encontrado
 *       500:
 *         description: Error del servidor
 */
router.get('/:id', async (req, res) => {
    try {
        const { rows } = await pool.query('SELECT * FROM puntos_venta WHERE id = $1', [req.params.id]);
        if (rows.length === 0) {
            return res.status(404).json({
                ok: false,
                data: null,
                message: 'Punto de venta no encontrado'
            });
        }
        return res.json({
            ok: true,
            data: rows[0],
            message: 'Punto de venta obtenido correctamente'
        });
    } catch (error) {
        console.error(`Error en GET /api/puntos/${req.params.id}:`, error);
        return res.status(500).json({
            ok: false,
            data: null,
            message: 'Error al obtener el punto de venta: ' + error.message
        });
    }
});

/**
 * @swagger
 * /api/puntos:
 *   post:
 *     summary: Crear un nuevo punto de venta
 *     description: Agrega un nuevo punto de venta al sistema con coordenadas y foto. Requiere API Key en cabecera.
 *     tags: [Puntos de Venta]
 *     security:
 *       - ApiKeyAuth: []
 *     requestBody:
 *       required: true
 *       content:
 *         multipart/form-data:
 *           schema:
 *             type: object
 *             required:
 *               - nombre
 *               - propietario
 *               - latitud
 *               - longitud
 *             properties:
 *               nombre:
 *                 type: string
 *               propietario:
 *                 type: string
 *               latitud:
 *                 type: number
 *                 format: float
 *               longitud:
 *                 type: number
 *                 format: float
 *               foto:
 *                 type: string
 *                 format: binary
 *     responses:
 *       201:
 *         description: Punto de venta creado con éxito
 *       400:
 *         description: Datos incompletos o incorrectos
 *       401:
 *         description: No autorizado
 *       500:
 *         description: Error del servidor
 */
router.post('/', requireApiKey, upload.single('foto'), async (req, res) => {
    try {
        const { nombre, propietario, latitud, longitud, horario_atencion } = req.body;

        if (!nombre || !propietario || !latitud || !longitud) {
            return res.status(400).json({
                ok: false,
                data: null,
                message: 'Faltan campos obligatorios: nombre, propietario, latitud y longitud.'
            });
        }

        let fotoUrl = null;
        if (req.file) {
            fotoUrl = await uploadToImgBB(req.file.path);
            if (!fotoUrl) {
                fotoUrl = `uploads/${req.file.filename}`;
            }
        }

        const { rows } = await pool.query(
            'INSERT INTO puntos_venta (nombre, propietario, latitud, longitud, foto, horario_atencion) VALUES ($1, $2, $3, $4, $5, $6) RETURNING *',
            [nombre, propietario, parseFloat(latitud), parseFloat(longitud), fotoUrl, horario_atencion || null]
        );

        return res.status(201).json({
            ok: true,
            data: rows[0],
            message: 'Punto de venta registrado exitosamente'
        });
    } catch (error) {
        console.error('Error en POST /api/puntos:', error);
        return res.status(500).json({
            ok: false,
            data: null,
            message: 'Error al registrar el punto de venta: ' + error.message
        });
    }
});

/**
 * @swagger
 * /api/puntos/{id}:
 *   put:
 *     summary: Actualizar un punto de venta existente
 *     description: Actualiza los detalles de un punto de venta por su ID. Requiere API Key en cabecera.
 *     tags: [Puntos de Venta]
 *     security:
 *       - ApiKeyAuth: []
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         schema:
 *           type: integer
 *         description: ID del punto de venta
 *     requestBody:
 *       required: true
 *       content:
 *         multipart/form-data:
 *           schema:
 *             type: object
 *             required:
 *               - nombre
 *               - propietario
 *               - latitud
 *               - longitud
 *             properties:
 *               nombre:
 *                 type: string
 *               propietario:
 *                 type: string
 *               latitud:
 *                 type: number
 *                 format: float
 *               longitud:
 *                 type: number
 *                 format: float
 *               foto:
 *                 type: string
 *                 format: binary
 *     responses:
 *       200:
 *         description: Punto de venta actualizado con éxito
 *       400:
 *         description: Datos incorrectos
 *       401:
 *         description: No autorizado
 *       404:
 *         description: Punto de venta no encontrado
 *       500:
 *         description: Error del servidor
 */
router.put('/:id', requireApiKey, upload.single('foto'), async (req, res) => {
    try {
        const { id } = req.params;
        const { nombre, propietario, latitud, longitud, horario_atencion } = req.body;

        if (!nombre || !propietario || !latitud || !longitud) {
            return res.status(400).json({
                ok: false,
                data: null,
                message: 'Faltan campos obligatorios para actualizar.'
            });
        }

        // Verificar si existe
        const { rows: existing } = await pool.query('SELECT * FROM puntos_venta WHERE id = $1', [id]);
        if (existing.length === 0) {
            return res.status(404).json({
                ok: false,
                data: null,
                message: 'Punto de venta no encontrado'
            });
        }

        let fotoUrl = existing[0].foto;

        if (req.file) {
            const uploadedUrl = await uploadToImgBB(req.file.path);
            if (uploadedUrl) {
                fotoUrl = uploadedUrl;
            } else {
                fotoUrl = `uploads/${req.file.filename}`;
            }
        }

        const { rows } = await pool.query(
            'UPDATE puntos_venta SET nombre = $1, propietario = $2, latitud = $3, longitud = $4, foto = $5, horario_atencion = $6 WHERE id = $7 RETURNING *',
            [nombre, propietario, parseFloat(latitud), parseFloat(longitud), fotoUrl, horario_atencion || null, id]
        );

        return res.json({
            ok: true,
            data: rows[0],
            message: 'Punto de venta actualizado correctamente'
        });
    } catch (error) {
        console.error(`Error en PUT /api/puntos/${req.params.id}:`, error);
        return res.status(500).json({
            ok: false,
            data: null,
            message: 'Error al actualizar el punto de venta: ' + error.message
        });
    }
});

/**
 * @swagger
 * /api/puntos/{id}:
 *   delete:
 *     summary: Eliminar un punto de venta
 *     description: Elimina físicamente un punto de venta de la base de datos. Requiere API Key en cabecera.
 *     tags: [Puntos de Venta]
 *     security:
 *       - ApiKeyAuth: []
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         schema:
 *           type: integer
 *         description: ID del punto de venta
 *     responses:
 *       200:
 *         description: Punto de venta eliminado con éxito
 *       401:
 *         description: No autorizado
 *       404:
 *         description: No encontrado
 *       500:
 *         description: Error del servidor
 */
router.delete('/:id', requireApiKey, async (req, res) => {
    try {
        const { id } = req.params;

        const { rows } = await pool.query('DELETE FROM puntos_venta WHERE id = $1 RETURNING *', [id]);
        if (rows.length === 0) {
            return res.status(404).json({
                ok: false,
                data: null,
                message: 'Punto de venta no encontrado'
            });
        }

        return res.json({
            ok: true,
            data: rows[0],
            message: 'Punto de venta eliminado correctamente'
        });
    } catch (error) {
        console.error(`Error en DELETE /api/puntos/${req.params.id}:`, error);
        return res.status(500).json({
            ok: false,
            data: null,
            message: 'Error al eliminar el punto de venta: ' + error.message
        });
    }
});

module.exports = router;
