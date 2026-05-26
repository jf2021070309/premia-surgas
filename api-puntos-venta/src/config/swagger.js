// ─── Configuración de Swagger / OpenAPI ──────────────────
const swaggerJsdoc = require('swagger-jsdoc');

const options = {
    definition: {
        openapi: '3.0.0',
        info: {
            title: 'API Puntos de Venta',
            version: '1.0.0',
            description: `
API REST para la gestión de **Puntos de Venta** (distribuidores).  
Diseñada para ser consumida por múltiples sistemas: PremiaSurgas, apps móviles, etc.

### Autenticación
Las rutas de **lectura** (GET) son públicas.  
Las rutas de **escritura** (POST, PUT, DELETE) requieren el header \`x-api-key\`.
            `,
            contact: {
                name: 'Surgas Dev Team'
            }
        },
        servers: [
            {
                url: 'http://localhost:3050',
                description: 'Desarrollo local'
            }
        ],
        components: {
            securitySchemes: {
                ApiKeyAuth: {
                    type: 'apiKey',
                    in: 'header',
                    name: 'x-api-key',
                    description: 'API Key para operaciones de escritura'
                }
            },
            schemas: {
                PuntoVenta: {
                    type: 'object',
                    properties: {
                        id:          { type: 'integer', example: 1 },
                        nombre:      { type: 'string',  example: 'Distribuidora Norte' },
                        propietario: { type: 'string',  example: 'Juan Pérez' },
                        latitud:     { type: 'number',  format: 'double', example: -18.025613 },
                        longitud:    { type: 'number',  format: 'double', example: -70.241629 },
                        foto:        { type: 'string',  nullable: true, example: 'https://i.ibb.co/abc123/foto.jpg' },
                        created_at:  { type: 'string',  format: 'date-time', example: '2026-05-26T12:00:00.000Z' }
                    }
                },
                PuntoVentaInput: {
                    type: 'object',
                    required: ['nombre', 'propietario', 'latitud', 'longitud'],
                    properties: {
                        nombre:      { type: 'string',  example: 'Distribuidora Norte' },
                        propietario: { type: 'string',  example: 'Juan Pérez' },
                        latitud:     { type: 'number',  format: 'double', example: -18.025613 },
                        longitud:    { type: 'number',  format: 'double', example: -70.241629 },
                        foto:        { type: 'string',  format: 'binary', description: 'Imagen del punto (opcional)' }
                    }
                },
                ApiResponse: {
                    type: 'object',
                    properties: {
                        ok:      { type: 'boolean' },
                        data:    { },
                        message: { type: 'string' }
                    }
                }
            }
        }
    },
    apis: ['./src/routes/*.js', './api-puntos-venta/src/routes/*.js']
};

const swaggerSpec = swaggerJsdoc(options);

module.exports = swaggerSpec;
