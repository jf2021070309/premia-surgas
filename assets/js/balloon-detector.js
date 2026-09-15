/**
 * assets/js/balloon-detector.js
 * Detector de balones de gas usando YOLOv8n via ONNX Runtime Web.
 * Reemplaza el algoritmo heurístico de color anterior.
 *
 * Dependencia: onnxruntime-web debe estar cargado antes de este script.
 * CDN: https://cdn.jsdelivr.net/npm/onnxruntime-web@1.18.0/dist/ort.min.js
 */

window.BalloonDetector = class BalloonDetector {

    /**
     * @param {Object} options
     * @param {HTMLCanvasElement} options.canvas          Canvas donde se dibuja el resultado
     * @param {number}            [options.expectedCount] Cantidad esperada de balones (0 = libre)
     * @param {Function}          [options.onCountChange] Callback(result) cuando cambia el conteo
     * @param {number}            [options.confThreshold] Umbral de confianza YOLO (0-1, default 0.35)
     * @param {number}            [options.iouThreshold]  IoU para NMS (0-1, default 0.45)
     */
    constructor(options = {}) {
        this.canvas        = options.canvas;
        this.ctx           = this.canvas ? this.canvas.getContext('2d') : null;
        this.expectedCount = options.expectedCount || 0;
        this.onCountChange = options.onCountChange || null;
        this.confThreshold = options.confThreshold ?? 0.35;
        this.iouThreshold  = options.iouThreshold  ?? 0.45;

        this.markers     = [];  // { x1, y1, x2, y2, conf, label, id }
        this.image       = null;
        this.isProcessing = false;

        // IDs de clase COCO que mapeamos como "balón de gas"
        // 39=bottle (más cercano), 75=vase, 10=fire hydrant
        this._balloonClasses = new Set([39, 75, 10]);

        this._session = null;
        this._sessionLoading = null;
        this._INPUT_SIZE = 640;

        this._COCO_NAMES = [
            'person','bicycle','car','motorcycle','airplane','bus','train','truck','boat',
            'traffic light','fire hydrant','stop sign','parking meter','bench','bird','cat',
            'dog','horse','sheep','cow','elephant','bear','zebra','giraffe','backpack',
            'umbrella','handbag','tie','suitcase','frisbee','skis','snowboard','sports ball',
            'kite','baseball bat','baseball glove','skateboard','surfboard','tennis racket',
            'bottle','wine glass','cup','fork','knife','spoon','bowl','banana','apple',
            'sandwich','orange','broccoli','carrot','hot dog','pizza','donut','cake','chair',
            'couch','potted plant','bed','dining table','toilet','tv','laptop','mouse','remote',
            'keyboard','cell phone','microwave','oven','toaster','sink','refrigerator','book',
            'clock','vase','scissors','teddy bear','hair drier','toothbrush'
        ];

        if (this.canvas) {
            this._setupInteractivity();
        }

        // Pre-cargar el modelo al instanciar
        this._ensureModel();
    }

    // ──────────────────────────────────────────────────────
    // API pública
    // ──────────────────────────────────────────────────────

    setExpectedCount(count) {
        this.expectedCount = parseInt(count, 10) || 0;
        this._emitCount();
    }

    /**
     * Carga una imagen (File, Blob o Data URL string) y ejecuta la detección YOLO.
     * @returns {Promise<void>}
     */
    async loadImage(imageSource) {
        const img = await this._loadImageEl(imageSource);
        this.image = img;
        await this.detectBalloons();
    }

    /**
     * Re-ejecuta la detección sobre la imagen cargada actualmente.
     * @returns {Promise<void>}
     */
    async detectBalloons() {
        if (!this.image || this.isProcessing) return;
        this.isProcessing = true;

        try {
            await this._ensureModel();
            const origW = this.image.naturalWidth  || this.image.width;
            const origH = this.image.naturalHeight || this.image.height;

            const { tensor, scale, dx, dy } = this._imageToTensor(this.image);
            const feeds = {};
            feeds[this._session.inputNames[0]] = tensor;
            const results = await this._session.run(feeds);

            this.markers = this._parseOutput(
                results[this._session.outputNames[0]],
                scale, dx, dy, origW, origH
            );

            this._redraw();
            this._emitCount();
        } catch (err) {
            console.error('[BalloonDetector] Error en detección YOLO:', err);
        } finally {
            this.isProcessing = false;
        }
    }

    getAnnotatedImage() {
        return this.canvas ? this.canvas.toDataURL('image/jpeg', 0.9) : null;
    }

    // ──────────────────────────────────────────────────────
    // Internos: modelo
    // ──────────────────────────────────────────────────────

    async _ensureModel() {
        if (this._session) return;
        if (this._sessionLoading) return this._sessionLoading;

        const MODEL_URL = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/premia-surgas/')
            + 'assets/models/yolov8n.onnx';

        this._sessionLoading = (async () => {
            try {
                if (typeof ort === 'undefined') {
                    throw new Error('ONNX Runtime Web no está cargado. Agrega el script de ort.min.js antes de balloon-detector.js.');
                }
                ort.env.wasm.numThreads = (navigator.hardwareConcurrency > 1) ? 4 : 1;
                ort.env.wasm.simd = true;

                const resp = await fetch(MODEL_URL);
                if (!resp.ok) throw new Error('HTTP ' + resp.status + ' al descargar modelo YOLO');
                const buf = await resp.arrayBuffer();

                this._session = await ort.InferenceSession.create(buf, {
                    executionProviders: ['webgl', 'wasm'],
                    graphOptimizationLevel: 'all',
                });
            } catch (e) {
                this._sessionLoading = null;
                throw e;
            }
        })();

        return this._sessionLoading;
    }

    // ──────────────────────────────────────────────────────
    // Internos: preprocesado
    // ──────────────────────────────────────────────────────

    _imageToTensor(imgEl) {
        const S = this._INPUT_SIZE;
        const c = document.createElement('canvas');
        c.width = S; c.height = S;
        const ctx = c.getContext('2d');

        const iw = imgEl.naturalWidth  || imgEl.width;
        const ih = imgEl.naturalHeight || imgEl.height;
        const scale = Math.min(S / iw, S / ih);
        const nw = Math.round(iw * scale);
        const nh = Math.round(ih * scale);
        const dx = (S - nw) / 2;
        const dy = (S - nh) / 2;

        ctx.fillStyle = '#808080';
        ctx.fillRect(0, 0, S, S);
        ctx.drawImage(imgEl, dx, dy, nw, nh);

        const { data } = ctx.getImageData(0, 0, S, S);
        const f32 = new Float32Array(3 * S * S);
        const area = S * S;

        for (let i = 0; i < area; i++) {
            f32[i]          = data[i * 4]     / 255;
            f32[area + i]   = data[i * 4 + 1] / 255;
            f32[area*2 + i] = data[i * 4 + 2] / 255;
        }

        return {
            tensor: new ort.Tensor('float32', f32, [1, 3, S, S]),
            scale, dx, dy
        };
    }

    // ──────────────────────────────────────────────────────
    // Internos: parseo de output [1, 84, 8400]
    // ──────────────────────────────────────────────────────

    _parseOutput(output, scale, dx, dy, origW, origH) {
        const data = output.data;
        const anchors = output.dims[2]; // 8400
        const raw = [];

        for (let a = 0; a < anchors; a++) {
            const cx = data[0 * anchors + a];
            const cy = data[1 * anchors + a];
            const bw = data[2 * anchors + a];
            const bh = data[3 * anchors + a];

            let maxConf = 0, classId = -1;
            for (let c = 0; c < 80; c++) {
                const s = data[(4 + c) * anchors + a];
                if (s > maxConf) { maxConf = s; classId = c; }
            }

            if (maxConf < this.confThreshold) continue;
            if (!this._balloonClasses.has(classId)) continue;

            const x1 = Math.max(0, (cx - bw/2 - dx) / scale);
            const y1 = Math.max(0, (cy - bh/2 - dy) / scale);
            const x2 = Math.min(origW, (cx + bw/2 - dx) / scale);
            const y2 = Math.min(origH, (cy + bh/2 - dy) / scale);

            if (x2 <= x1 || y2 <= y1) continue;

            raw.push({
                classId,
                label: this._COCO_NAMES[classId] || ('cls_' + classId),
                conf: maxConf, x1, y1, x2, y2
            });
        }

        const kept = this._nms(raw, this.iouThreshold);
        kept.sort((a, b) => a.x1 - b.x1);
        return kept.map((d, i) => ({ ...d, id: i + 1 }));
    }

    _iou(a, b) {
        const x1 = Math.max(a.x1, b.x1), y1 = Math.max(a.y1, b.y1);
        const x2 = Math.min(a.x2, b.x2), y2 = Math.min(a.y2, b.y2);
        const inter = Math.max(0, x2 - x1) * Math.max(0, y2 - y1);
        const ua = (a.x2-a.x1)*(a.y2-a.y1) + (b.x2-b.x1)*(b.y2-b.y1) - inter;
        return inter / (ua + 1e-6);
    }

    _nms(dets, iouThresh) {
        dets.sort((a, b) => b.conf - a.conf);
        const keep = [], supp = new Set();
        for (let i = 0; i < dets.length; i++) {
            if (supp.has(i)) continue;
            keep.push(dets[i]);
            for (let j = i + 1; j < dets.length; j++) {
                if (!supp.has(j) && this._iou(dets[i], dets[j]) > iouThresh) supp.add(j);
            }
        }
        return keep;
    }

    // ──────────────────────────────────────────────────────
    // Internos: dibujo
    // ──────────────────────────────────────────────────────

    _redraw() {
        if (!this.ctx || !this.image) return;

        const origW = this.image.naturalWidth  || this.image.width;
        const origH = this.image.naturalHeight || this.image.height;
        const maxW  = 800;
        const ds    = Math.min(1, maxW / origW);
        const dW    = Math.round(origW * ds);
        const dH    = Math.round(origH * ds);

        this.canvas.width  = dW;
        this.canvas.height = dH;
        this.ctx.drawImage(this.image, 0, 0, dW, dH);

        const COLORS = { 39: '#ea580c', 10: '#7c3aed', 75: '#0284c7' };
        const isVerified = this.expectedCount > 0 && this.markers.length === this.expectedCount;
        const verColor   = isVerified ? '#10b981' : '#f59e0b';

        this.markers.forEach((m, idx) => {
            const color = this.expectedCount > 0
                ? verColor
                : (COLORS[m.classId] || '#10b981');

            const x1 = m.x1 * ds, y1 = m.y1 * ds;
            const bw = (m.x2 - m.x1) * ds;
            const bh = (m.y2 - m.y1) * ds;

            this.ctx.save();
            this.ctx.strokeStyle = color;
            this.ctx.lineWidth = 3;
            this.ctx.fillStyle = color + '28';
            this.ctx.strokeRect(x1, y1, bw, bh);
            this.ctx.fillRect(x1, y1, bw, bh);

            // Esquinas blancas
            const cL = Math.min(14, bw * 0.22, bh * 0.22);
            this.ctx.strokeStyle = '#fff'; this.ctx.lineWidth = 2.5;
            this.ctx.beginPath(); this.ctx.moveTo(x1, y1+cL); this.ctx.lineTo(x1, y1); this.ctx.lineTo(x1+cL, y1); this.ctx.stroke();
            this.ctx.beginPath(); this.ctx.moveTo(x1+bw-cL, y1+bh); this.ctx.lineTo(x1+bw, y1+bh); this.ctx.lineTo(x1+bw, y1+bh-cL); this.ctx.stroke();

            // Badge
            const num = idx + 1;
            const badgeW = 60, badgeH = 26;
            const bX = x1 + bw/2 - badgeW/2;
            const bY = Math.max(5, y1 - badgeH - 4);

            this.ctx.fillStyle = color;
            this.ctx.beginPath();
            if (this.ctx.roundRect) { this.ctx.roundRect(bX, bY, badgeW, badgeH, 6); }
            else { this.ctx.rect(bX, bY, badgeW, badgeH); }
            this.ctx.fill();

            this.ctx.fillStyle = '#fff';
            this.ctx.font = 'bold 13px Inter, sans-serif';
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.fillText('Balon #' + num, bX + badgeW/2, bY + badgeH/2);

            this.ctx.restore();
        });

        // Banner inferior
        this.ctx.save();
        const bannerTxt = 'YOLOv8  ' + this.markers.length + ' balon' + (this.markers.length !== 1 ? 'es' : '')
            + (this.expectedCount > 0 ? '  (Esperados: ' + this.expectedCount + ')' : '');
        this.ctx.font = 'bold 12px Inter, sans-serif';
        const tW = this.ctx.measureText(bannerTxt).width + 24;
        this.ctx.fillStyle = 'rgba(15,23,42,0.78)';
        this.ctx.fillRect(10, dH - 35, tW, 26);
        this.ctx.fillStyle = '#fff'; this.ctx.textAlign = 'left'; this.ctx.textBaseline = 'middle';
        this.ctx.fillText(bannerTxt, 22, dH - 22);
        this.ctx.restore();
    }

    // ──────────────────────────────────────────────────────
    // Internos: interactividad
    // ──────────────────────────────────────────────────────

    _setupInteractivity() {
        this.canvas.addEventListener('click', (e) => {
            if (!this.image || !this.markers.length) return;

            const rect = this.canvas.getBoundingClientRect();
            const cx = (e.clientX - rect.left) * (this.canvas.width / rect.width);
            const cy = (e.clientY - rect.top)  * (this.canvas.height / rect.height);

            const origW = this.image.naturalWidth || this.image.width;
            const ds = Math.min(1, 800 / origW);

            const idx = this.markers.findIndex(m =>
                cx >= m.x1*ds && cx <= m.x2*ds && cy >= m.y1*ds && cy <= m.y2*ds
            );

            if (idx >= 0) {
                this.markers.splice(idx, 1);
                this.markers.forEach((m, i) => { m.id = i + 1; });
                this._redraw();
                this._emitCount();
            }
        });
    }

    // ──────────────────────────────────────────────────────
    // Internos: utilidades
    // ──────────────────────────────────────────────────────

    _loadImageEl(imageSource) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;

            if (typeof imageSource === 'string') {
                img.src = imageSource;
            } else if (imageSource instanceof File || imageSource instanceof Blob) {
                const reader = new FileReader();
                reader.onload = (e) => { img.src = e.target.result; };
                reader.onerror = reject;
                reader.readAsDataURL(imageSource);
            } else {
                reject(new Error('Formato de imagen inválido'));
            }
        });
    }

    _emitCount() {
        const count = this.markers.length;
        const isVerified = this.expectedCount > 0 && count === this.expectedCount;

        if (typeof this.onCountChange === 'function') {
            this.onCountChange({
                count,
                expected: this.expectedCount,
                isVerified,
                alertText: isVerified ? '# DE BALONES VERIFICADO (CHECK)' : null,
                dataUrl: this.canvas ? this.canvas.toDataURL('image/jpeg', 0.9) : null,
            });
        }
    }
};
