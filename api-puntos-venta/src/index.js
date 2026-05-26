require('dotenv').config();
const express = require('express');
const cors = require('cors');
const path = require('path');
const swaggerUi = require('swagger-ui-express');
const swaggerSpec = require('./config/swagger');
const puntosRouter = require('./routes/puntos');

const app = express();
const PORT = process.env.PORT || 3050;

// ─── Middlewares Globales ───────────────────────────────
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Servir de manera estática los archivos subidos localmente (por si falla ImgBB)
app.use('/uploads', express.static(path.join(__dirname, '../uploads')));

// ─── Swagger Documentation Route ────────────────────────
app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));

// ─── Rutas de la API ────────────────────────────────────
app.use('/api/puntos', puntosRouter);

// ─── Ruta de prueba simple ──────────────────────────────
app.get('/', (req, res) => {
    res.json({
        name: 'API Puntos de Venta Surgas',
        version: '1.0.0',
        docs: `http://localhost:${PORT}/api-docs`,
        message: 'Servidor funcionando correctamente'
    });
});

// ─── Manejo de errores global ───────────────────────────
app.use((err, req, res, next) => {
    console.error('API Error:', err.message);
    res.status(500).json({
        ok: false,
        data: null,
        message: err.message || 'Error interno del servidor'
    });
});

// ─── Levantar servidor ──────────────────────────────────
app.listen(PORT, () => {
    console.log(`===============================================`);
    console.log(`🚀 API Puntos de Venta corriendo en:`);
    console.log(`👉 http://localhost:${PORT}`);
    console.log(`📄 Documentación Swagger disponible en:`);
    console.log(`👉 http://localhost:${PORT}/api-docs`);
    console.log(`===============================================`);
});
