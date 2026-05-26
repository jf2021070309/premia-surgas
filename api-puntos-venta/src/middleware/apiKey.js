// ─── Middleware: validar API Key en rutas de escritura ───
function requireApiKey(req, res, next) {
    const key = req.headers['x-api-key'];
    
    if (!key || key !== process.env.API_KEY) {
        return res.status(401).json({
            ok: false,
            data: null,
            message: 'API Key inválida o no proporcionada'
        });
    }
    
    next();
}

module.exports = requireApiKey;
// 
