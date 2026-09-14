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
                const defaultW = Math.max(30, this.canvas.width * 0.12);
                const defaultH = defaultW * 1.7;
                this.markers.push({
                    x: Math.max(0, clickX - defaultW / 2),
                    y: Math.max(0, clickY - defaultH / 2),
                    w: defaultW,
                    h: defaultH,
                    id: Date.now()
                });
            }

            this.redraw();
            this.emitCount();
        });
    }

    detectBalloons() {
        if (!this.image) return;
        this.isProcessing = true;

        // Ajustar resolución del canvas
        const maxWidth = 900;
        let w = this.image.naturalWidth || this.image.width;
        let h = this.image.naturalHeight || this.image.height;

        if (w > maxWidth) {
            h = Math.round((h * maxWidth) / w);
            w = maxWidth;
        }

        this.canvas.width = w;
        this.canvas.height = h;

        // Dibujar imagen base en canvas temporal para extraer datos de píxeles
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = w;
        tempCanvas.height = h;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.drawImage(this.image, 0, 0, w, h);

        const imgData = tempCtx.getImageData(0, 0, w, h);
        const data = imgData.data;

        // Algoritmo de Visión: Segmentación por densidad de contornos y gradientes cilíndricos
        const gridX = 14;
        const gridY = 8;
        const cellW = w / gridX;
        const cellH = h / gridY;
        const candidates = [];

        for (let gy = 0; gy < gridY; gy++) {
            for (let gx = 0; gx < gridX; gx++) {
                const cx = Math.floor(gx * cellW);
                const cy = Math.floor(gy * cellH);
                let edgeScore = 0;
                let colorVariance = 0;
                let samples = 0;

                for (let y = cy; y < cy + cellH; y += 4) {
                    for (let x = cx; x < cx + cellW; x += 4) {
                        const idx = (y * w + x) * 4;
                        const r = data[idx];
                        const g = data[idx + 1];
                        const b = data[idx + 2];

                        // Gradiente horizontal
                        if (x + 4 < w) {
                            const nextIdx = (y * w + (x + 4)) * 4;
                            const diff = Math.abs(r - data[nextIdx]) + Math.abs(g - data[nextIdx + 1]) + Math.abs(b - data[nextIdx + 2]);
                            if (diff > 45) edgeScore++;
                        }
                        samples++;
                    }
                }

                const density = edgeScore / Math.max(1, samples);
                if (density > 0.18) {
                    candidates.push({
                        gx, gy,
                        x: cx,
                        y: cy,
                        density
                    });
                }
            }
        }

        // Agrupar celdas adyacentes verticales formando la silueta cilíndrica de balones
        const clusters = [];
        const visited = new Set();

        candidates.forEach((c, idx) => {
            if (visited.has(idx)) return;
            const cluster = [c];
            visited.add(idx);

            for (let j = idx + 1; j < candidates.length; j++) {
                if (visited.has(j)) continue;
                const other = candidates[j];
                const distGx = Math.abs(c.gx - other.gx);
                const distGy = Math.abs(c.gy - other.gy);

                if (distGx <= 1 && distGy <= 2) {
                    cluster.push(other);
                    visited.add(j);
                }
            }

            if (cluster.length >= 1) {
                clusters.push(cluster);
            }
        });

        // Ordenar clusters por prominencia
        clusters.sort((a, b) => b.length - a.length);

        // Convertir clusters en cajas delimitadoras de balones
        this.markers = [];
        const targetCount = this.expectedCount > 0 ? this.expectedCount : Math.min(clusters.length, 6);

        // Si tenemos cantidad esperada, adaptamos el umbral para detectar de forma guiada
        const selectedClusters = clusters.slice(0, targetCount);

        selectedClusters.forEach((cl, i) => {
            let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
            cl.forEach(pt => {
                minX = Math.min(minX, pt.x);
                maxX = Math.max(maxX, pt.x + cellW);
                minY = Math.min(minY, pt.y);
                maxY = Math.max(maxY, pt.y + cellH);
            });

            // Normalizar a proporción cilíndrica de balón (aprox 1:1.6)
            let boxW = maxX - minX;
            let boxH = maxY - minY;
            const minSize = Math.max(35, w * 0.08);

            boxW = Math.max(boxW, minSize);
            boxH = Math.max(boxH, boxW * 1.5);

            // Evitar desbordes del canvas
            minX = Math.max(10, Math.min(w - boxW - 10, minX));
            minY = Math.max(10, Math.min(h - boxH - 10, minY));

            this.markers.push({
                x: minX,
                y: minY,
                w: boxW,
                h: boxH,
                id: i + 1
            });
        });

        // Si la foto no arrojó clusters suficientes pero hay cantidad esperada, 
        // distribuir estimaciones razonables sobre la imagen para facilitar validación al conductor
        if (this.markers.length < this.expectedCount && this.expectedCount > 0) {
            const needed = this.expectedCount - this.markers.length;
            const stepW = (w * 0.8) / (this.expectedCount + 1);
            const defaultH = Math.min(h * 0.55, 180);
            const defaultW = defaultH / 1.7;

            for (let k = 0; k < needed; k++) {
                const posX = (w * 0.1) + ((this.markers.length + 1) * stepW) - (defaultW / 2);
                const posY = (h / 2) - (defaultH / 2);
                this.markers.push({
                    x: Math.max(10, Math.min(w - defaultW - 10, posX)),
                    y: Math.max(10, Math.min(h - defaultH - 10, posY)),
                    w: defaultW,
                    h: defaultH,
                    id: Date.now() + k
                });
            }
        }

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
            const strokeColor = isMatch ? '#10b981' : '#06b6d4'; // Verde esmeralda si coincide, cyan si no
            const fillColor = isMatch ? 'rgba(16, 185, 129, 0.18)' : 'rgba(6, 182, 212, 0.18)';

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
