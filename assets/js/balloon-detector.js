/**
 * assets/js/balloon-detector.js
 * Algoritmo de visión interactivo en cliente para recuento y verificación de balones de gas.
 */

window.BalloonDetector = class BalloonDetector {
    constructor(options = {}) {
        this.canvas = options.canvas;
        this.ctx = this.canvas ? this.canvas.getContext('2d') : null;
        this.expectedCount = options.expectedCount || 0;
        this.onCountChange = options.onCountChange || null;
        this.markers = []; // { x, y, w, h, id }
        this.image = null;
        this.scale = 1;
        this.isProcessing = false;

        if (this.canvas) {
            this.setupInteractivity();
        }
    }

    setExpectedCount(count) {
        this.expectedCount = parseInt(count, 10) || 0;
        this.emitCount();
    }

    loadImage(imageSource) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                this.image = img;
                this.detectBalloons();
                resolve();
            };
            img.onerror = reject;

            if (typeof imageSource === 'string') {
                img.src = imageSource;
            } else if (imageSource instanceof File || imageSource instanceof Blob) {
                const reader = new FileReader();
                reader.onload = (e) => { img.src = e.target.result; };
                reader.onerror = reject;
                reader.readAsDataURL(imageSource);
            } else {
                reject(new Error("Formato de imagen inválido"));
            }
        });
    }

    setupInteractivity() {
        this.canvas.addEventListener('click', (e) => {
            if (!this.image) return;

            const rect = this.canvas.getBoundingClientRect();
            const clickX = (e.clientX - rect.left) * (this.canvas.width / rect.width);
            const clickY = (e.clientY - rect.top) * (this.canvas.height / rect.height);

            // Verificar si hizo clic dentro de un marcador existente para eliminarlo
            const removeIndex = this.markers.findIndex(m => {
                return clickX >= m.x && clickX <= (m.x + m.w) && clickY >= m.y && clickY <= (m.y + m.h);
            });

            if (removeIndex >= 0) {
                this.markers.splice(removeIndex, 1);
            } else {
                // Agregar nuevo marcador centrado en el clic
                const defaultW = Math.max(35, Math.round(this.canvas.width * 0.15));
                const defaultH = Math.round(defaultW * 1.65);
                this.markers.push({
                    x: Math.max(5, Math.round(clickX - defaultW / 2)),
                    y: Math.max(10, Math.round(clickY - defaultH / 2)),
                    w: defaultW,
                    h: defaultH,
                    id: Date.now()
                });
            }

            // Ordenar de izquierda a derecha y re-numerar
            this.markers.sort((a, b) => a.x - b.x);
            this.markers.forEach((m, idx) => { m.id = idx + 1; });

            this.redraw();
            this.emitCount();
        });
    }

    detectBalloons() {
        if (!this.image) return;
        this.isProcessing = true;

        // Ajustar resolución de trabajo
        const maxWidth = 800;
        let w = this.image.naturalWidth || this.image.width;
        let h = this.image.naturalHeight || this.image.height;

        if (w > maxWidth) {
            h = Math.round((h * maxWidth) / w);
            w = maxWidth;
        }

        this.canvas.width = w;
        this.canvas.height = h;

        // Extraer mapa de píxeles
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = w;
        tempCanvas.height = h;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.drawImage(this.image, 0, 0, w, h);

        const imgData = tempCtx.getImageData(0, 0, w, h);
        const data = imgData.data;

        // ══════════════════════════════════════════════════════════════
        // ALGORITMO DE DETECCIÓN REAL DE BALONES DE GAS (10KG)
        // ══════════════════════════════════════════════════════════════
        // Zona vertical donde se asientan los balones (excluyendo el techo/pared alta)
        const yTopSearch = Math.floor(h * 0.20);
        const yBottomSearch = Math.floor(h * 0.92);
        const searchHeight = yBottomSearch - yTopSearch;

        // Helper para evaluar si un píxel corresponde a pintura de balón de gas
        function isCylinderPixel(r, g, b) {
            const maxC = Math.max(r, g, b);
            const minC = Math.min(r, g, b);
            const chroma = maxC - minC;

            // 1. Balón Morado / Violeta (Surgas / Solgas): R y B dominan sobre G
            const isPurple = (r > g + 10) && (b > g + 6) && (r > 35 || b > 35);
            // 2. Balón Rojo / Vino (Lima Gas): R dominante
            const isRed = (r > g + 20) && (r > b + 20) && (r > 55);
            // 3. Balón Celeste / Turquesa (Zeta Gas): B y G dominan sobre R
            const isCyan = (b > r + 10) && (g > r + 5) && (b > 60);
            // 4. Balón Amarillo / Naranja:
            const isYellow = (r > b + 25) && (g > b + 15);
            // 5. Saturación general de pintura vs pared gris o cal
            const isSaturated = chroma > 22 && maxC > 45;

            return (isPurple || isRed || isCyan || isYellow || isSaturated);
        }

        // Analizar densidad de presencia cilíndrica por columna horizontal X
        const stepX = 2; // muestreo cada 2px
        const numCols = Math.floor(w / stepX);
        const colScores = new Float32Array(numCols);
        const colBrightness = new Float32Array(numCols);
        const colTops = new Int32Array(numCols);
        const colBottoms = new Int32Array(numCols);

        for (let c = 0; c < numCols; c++) {
            const x = c * stepX;
            let matchCount = 0;
            let sumLum = 0;
            let firstY = -1;
            let lastY = -1;

            for (let y = yTopSearch; y < yBottomSearch; y += 3) {
                const idx = (y * w + x) * 4;
                const r = data[idx];
                const g = data[idx + 1];
                const b = data[idx + 2];
                const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                sumLum += lum;

                if (isCylinderPixel(r, g, b)) {
                    matchCount++;
                    if (firstY === -1) firstY = y;
                    lastY = y;
                }
            }

            const totalSamples = Math.floor(searchHeight / 3);
            colScores[c] = matchCount / Math.max(1, totalSamples);
            colBrightness[c] = sumLum / totalSamples;
            colTops[c] = firstY > -1 ? firstY : Math.floor(h * 0.32);
            colBottoms[c] = lastY > -1 ? lastY : Math.floor(h * 0.88);
        }

        // Segmentar regiones continuas con presencia de balones
        const THRESHOLD = 0.20;
        const rawSegments = [];
        let inSeg = false;
        let startCol = 0;

        for (let c = 0; c < numCols; c++) {
            const hasBalon = colScores[c] >= THRESHOLD;
            if (hasBalon && !inSeg) {
                inSeg = true;
                startCol = c;
            } else if (!hasBalon && inSeg) {
                inSeg = false;
                rawSegments.push({ start: startCol, end: c });
            }
        }
        if (inSeg) {
            rawSegments.push({ start: startCol, end: numCols - 1 });
        }

        // Ancho típico de un balón de 10kg en el encuadre (en columnas)
        const minCylCols = Math.floor((w * 0.08) / stepX);
        const typicalCylCols = Math.floor((w * 0.16) / stepX);
        const maxSingleCylCols = Math.floor((w * 0.24) / stepX);

        const cylinderSegments = [];

        rawSegments.forEach(seg => {
            const segCols = seg.end - seg.start;
            if (segCols < minCylCols) {
                // Demasiado angosto para ser un balón completo (ruido o reflejo)
                return;
            }

            // Si el bloque es ancho (> maxSingleCylCols), contiene balones contiguos pegados
            if (segCols > maxSingleCylCols) {
                // Determinar cuántos balones caben físicamente en este bloque
                const numBalones = Math.max(2, Math.round(segCols / typicalCylCols));
                const partWidth = Math.floor(segCols / numBalones);

                for (let k = 0; k < numBalones; k++) {
                    const sStart = seg.start + k * partWidth;
                    const sEnd = (k === numBalones - 1) ? seg.end : (sStart + partWidth);
                    cylinderSegments.push({ start: sStart, end: sEnd });
                }
            } else {
                cylinderSegments.push(seg);
            }
        });

        // Crear cajas delimitadoras reales (SIN inventar ni forzar)
        this.markers = [];

        cylinderSegments.forEach((seg, i) => {
            const x1 = seg.start * stepX;
            const x2 = seg.end * stepX;
            const boxW = Math.max(30, x2 - x1);

            let sumT = 0, sumB = 0, cnt = 0;
            for (let c = seg.start; c <= seg.end; c++) {
                sumT += colTops[c];
                sumB += colBottoms[c];
                cnt++;
            }
            const avgT = Math.floor(sumT / Math.max(1, cnt));
            const avgB = Math.floor(sumB / Math.max(1, cnt));

            // Proporción cilíndrica de balón (altura aprox 1.5 a 1.9 veces el ancho)
            let boxH = avgB - avgT;
            const targetH = Math.round(boxW * 1.65);
            if (boxH < targetH * 0.8) boxH = targetH;

            let finalY = Math.max(10, avgT - Math.round(boxH * 0.1));
            let finalH = Math.min(h - finalY - 8, boxH);

            this.markers.push({
                x: Math.max(5, x1),
                y: finalY,
                w: boxW,
                h: finalH,
                id: i + 1
            });
        });

        // Ordenar de izquierda a derecha
        this.markers.sort((a, b) => a.x - b.x);
        this.markers.forEach((m, idx) => { m.id = idx + 1; });

        // NOTA: No se fuerza this.expectedCount. Si la foto tiene 4 balones,
        // se reportan exactamente 4 balones, alertando la discrepancia.

        this.isProcessing = false;
        this.redraw();
        this.emitCount();
    }

    redraw() {
        if (!this.ctx || !this.image) return;
        const w = this.canvas.width;
        const h = this.canvas.height;

        // Limpiar y dibujar la foto
        this.ctx.clearRect(0, 0, w, h);
        this.ctx.drawImage(this.image, 0, 0, w, h);

        // Dibujar cada marcador de balón detectado
        this.markers.forEach((m, idx) => {
            const num = idx + 1;
            const isMatch = this.expectedCount > 0 && this.markers.length === this.expectedCount;
            const strokeColor = isMatch ? '#10b981' : '#f59e0b'; // Verde si coincide, ámbar de advertencia si no
            const fillColor = isMatch ? 'rgba(16, 185, 129, 0.18)' : 'rgba(245, 158, 11, 0.18)';

            // Caja delimitadora con bordes redondeados
            this.ctx.save();
            this.ctx.strokeStyle = strokeColor;
            this.ctx.lineWidth = 3.5;
            this.ctx.fillStyle = fillColor;

            // Dibujar rectángulo
            this.ctx.strokeRect(m.x, m.y, m.w, m.h);
            this.ctx.fillRect(m.x, m.y, m.w, m.h);

            // Esquinas reforzadas
            const cLen = Math.min(15, m.w * 0.25);
            this.ctx.strokeStyle = '#ffffff';
            this.ctx.lineWidth = 2.5;
            // TL
            this.ctx.beginPath();
            this.ctx.moveTo(m.x, m.y + cLen);
            this.ctx.lineTo(m.x, m.y);
            this.ctx.lineTo(m.x + cLen, m.y);
            this.ctx.stroke();
            // BR
            this.ctx.beginPath();
            this.ctx.moveTo(m.x + m.w - cLen, m.y + m.h);
            this.ctx.lineTo(m.x + m.w, m.y + m.h);
            this.ctx.lineTo(m.x + m.w, m.y + m.h - cLen);
            this.ctx.stroke();

            // Etiqueta numerada con ícono
            const badgeW = 60;
            const badgeH = 26;
            const badgeX = m.x + (m.w / 2) - (badgeW / 2);
            const badgeY = Math.max(5, m.y - badgeH - 4);

            this.ctx.fillStyle = strokeColor;
            this.ctx.beginPath();
            if (this.ctx.roundRect) {
                this.ctx.roundRect(badgeX, badgeY, badgeW, badgeH, 6);
            } else {
                this.ctx.rect(badgeX, badgeY, badgeW, badgeH);
            }
            this.ctx.fill();

            // Texto de número de balón
            this.ctx.fillStyle = '#ffffff';
            this.ctx.font = 'bold 13px Inter, sans-serif';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText(`Balón #${num}`, badgeX + (badgeW / 2), badgeY + (badgeH / 2));

            this.ctx.restore();
        });

        // Banner informativo flotante dentro del canvas
        this.ctx.save();
        const bannerText = `Recuento: ${this.markers.length} balones ${this.expectedCount > 0 ? '(Esperados: ' + this.expectedCount + ')' : ''}`;
        this.ctx.font = 'bold 12px Inter, sans-serif';
        const tWidth = this.ctx.measureText(bannerText).width;

        this.ctx.fillStyle = 'rgba(15, 23, 42, 0.75)';
        this.ctx.fillRect(10, h - 35, tWidth + 24, 26);
        this.ctx.fillStyle = '#ffffff';
        this.ctx.textAlign = 'left';
        this.ctx.textBaseline = 'middle';
        this.ctx.fillText(bannerText, 22, h - 22);
        this.ctx.restore();
    }

    emitCount() {
        const count = this.markers.length;
        const isVerified = this.expectedCount > 0 && count === this.expectedCount;

        if (typeof this.onCountChange === 'function') {
            this.onCountChange({
                count: count,
                expected: this.expectedCount,
                isVerified: isVerified,
                alertText: isVerified ? '# DE BALONES VERIFICADO (CHECK)' : null,
                dataUrl: this.canvas.toDataURL('image/jpeg', 0.9)
            });
        }
    }

    getAnnotatedImage() {
        return this.canvas ? this.canvas.toDataURL('image/jpeg', 0.9) : null;
    }
};
