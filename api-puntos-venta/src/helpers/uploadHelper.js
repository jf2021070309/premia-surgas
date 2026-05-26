const fs = require('fs');
const path = require('path');

// Asegurar que exista la carpeta temporal de subidas en la API
const uploadsDir = path.join(__dirname, '../../uploads');
if (!fs.existsSync(uploadsDir)) {
    fs.mkdirSync(uploadsDir, { recursive: true });
}

/**
 * Sube una imagen a ImgBB a partir de una ruta de archivo local.
 * Utiliza fetch nativo (disponible en Node.js 18+).
 * 
 * @param {string} localFilePath Ruta local del archivo
 * @returns {Promise<string|null>} URL de la imagen en ImgBB o null si falla
 */
async function uploadToImgBB(localFilePath) {
    const apiKey = process.env.IMGBB_API_KEY;
    if (!apiKey || apiKey === 'your_imgbb_api_key') {
        console.error('ImgBB Error: API Key no configurada en las variables de entorno de la API.');
        return null;
    }

    try {
        if (!fs.existsSync(localFilePath)) {
            console.error('ImgBB Error: El archivo temporal no existe en la ruta:', localFilePath);
            return null;
        }

        const fileData = fs.readFileSync(localFilePath);
        const base64Image = fileData.toString('base64');

        const formData = new URLSearchParams();
        formData.append('image', base64Image);

        const response = await fetch(`https://api.imgbb.com/1/upload?key=${apiKey}`, {
            method: 'POST',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        const result = await response.json();

        if (result && result.success && result.data && result.data.url) {
            return result.data.url;
        } else {
            console.error('ImgBB API Error:', result?.error?.message || 'Error desconocido');
            return null;
        }
    } catch (error) {
        console.error('Error en uploadToImgBB:', error);
        return null;
    } finally {
        // Intentar eliminar el archivo temporal
        try {
            if (fs.existsSync(localFilePath)) {
                fs.unlinkSync(localFilePath);
            }
        } catch (unlinkError) {
            console.error('Error al eliminar archivo temporal de subida:', unlinkError);
        }
    }
}

module.exports = {
    uploadToImgBB
};
