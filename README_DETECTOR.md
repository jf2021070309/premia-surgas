# 🔍 Módulo de Visión Artificial: Detección y Conteo de Balones de Gas (GLP)

Documentación técnica del sistema de detección automática de cilindros/balones de gas para **PremiaSurgas**. Comprende tanto el laboratorio interactivo en frontend ([`detector_lab.html`](file:///c:/xampp/htdocs/premia-surgas/detector_lab.html)) como la **arquitectura recomendada para producción** basada en un microservicio interno Python (FastAPI + ONNX Runtime).

---

## 📌 Resumen Ejecutivo

| Parámetro | Enfoque Frontend (Laboratorio) | Enfoque Producción (Microservicio Interno) |
|---|---|---|
| **Tecnología Base** | TensorFlow.js (`v4.21.0`) + COCO-SSD | **FastAPI** + **ONNX Runtime (CPU)** |
| **Modelo** | `lite_mobilenet_v2` (clases proxy genéricas) | **YOLOv8 Nano especializado** (`yolov8_balones.onnx`) |
| **Dataset de Entrenamiento** | Microsoft COCO (sin balones GLP) | **[`CylinDeRS_new4_wscript.v3i.yolov8`](file:///c:/xampp/htdocs/premia-surgas/CylinDeRS_new4_wscript.v3i.yolov8)** (18,137+ fotos de cilindros) |
| **Entorno de Red** | Navegador del usuario (Client-Side) | **127.0.0.1:8001** (Loopback privado, invisible a internet) |
| **Inferencia** | SAHI 3×3 con NMS híbrido | Inferencia directa / SAHI en CPU + NMS Híbrido |
| **Consumo RAM en VPS** | 0 MB en servidor (corre en cliente) | **~120 - 180 MB en reposo** (Pico: +50-80 MB) |
| **Precisión Esperada** | 50% – 70% (dependiente del color/luz) | **> 98%** (entrenado exclusivamente para balones) |

---

## 🧠 Arquitectura de Producción: Microservicio Python Localhost

```
   [ Celular / PC del Conductor o Afiliado ]
                     │  (Sube foto + balones_declarados)
                     ▼
   [ Apache / Nginx + PHP (PremiaSurgas) ]
                     │
                     │  POST http://127.0.0.1:8001/detectar
                     │  (Llamada interna cURL, NUNCA expuesta a internet)
                     ▼
   ┌────────────────────────────────────────────────────────┐
   │  Microservicio Python (FastAPI + ONNX Runtime en CPU)  │
   │  • Escucha en 127.0.0.1:8001                           │
   │  • Modelo: yolov8_balones.onnx                         │
   │  • NMS Híbrido: IoU + Supresión por Contenimiento      │
   │  • Systemd Unit: MemoryMax = 600M                      │
   └────────────────────────────────────────────────────────┘
                     │
                     ▼  JSON: {"detectados": N, "declarados": M, "coincide": bool}
   [ Controlador PHP (PremiaSurgas) ]
        │
        ├─► Si coincide: ASIGNACIÓN AUTOMÁTICA DE PUNTOS
        │
        └─► Si NO coincide: BANDEJA DE REVISIÓN MANUAL
            (Evita fricción con conductores por falsos rechazos)
```

---

## 📦 Dataset de Entrenamiento Disponible en el Proyecto

En la raíz del proyecto se encuentra el dataset etiquetado:
📁 **[`CylinDeRS_new4_wscript.v3i.yolov8/`](file:///c:/xampp/htdocs/premia-surgas/CylinDeRS_new4_wscript.v3i.yolov8/)**

* **Clase**: `names: ['gas_cylinder']` (nc: 1).
* **Volumen**:
  * **Train**: 18,137 imágenes etiquetadas.
  * **Validation**: 4,862 imágenes.
  * **Test**: 2,270 imágenes.
* **Origen**: Roboflow Universe (`cylindersnew/cylinders_new4_wscript/3`).

### Script de Entrenamiento Rápido (`train_yolov8.py`)
Ubicado en [`detector_service/train_yolov8.py`](file:///c:/xampp/htdocs/premia-surgas/detector_service/train_yolov8.py):
```bash
pip install ultralytics onnx
python detector_service/train_yolov8.py --epochs 30 --batch 16
```
Genera automáticamente el archivo optimizado **`detector_service/yolov8_balones.onnx`** (~6 MB).

---

## 💻 Implementación del Microservicio Python (`balon_service.py`)

Archivo completo: [`detector_service/balon_service.py`](file:///c:/xampp/htdocs/premia-surgas/detector_service/balon_service.py).

### Estructura y Código Principal
```python
from fastapi import FastAPI, UploadFile, File, Form, HTTPException
import onnxruntime as ort
import numpy as np
from PIL import Image
import io

app = FastAPI(title="PremiaSurgas Balon Detector")
session = ort.InferenceSession("yolov8_balones.onnx", providers=["CPUExecutionProvider"])

def preprocesar(img_bytes: bytes):
    img = Image.open(io.BytesIO(img_bytes)).convert("RGB")
    orig_w, orig_h = img.size
    img_resized = img.resize((640, 640))
    arr = np.array(img_resized).astype(np.float32) / 255.0
    arr = arr.transpose(2, 0, 1)[None, :]  # HWC -> NCHW
    return arr, orig_w, orig_h

def calcular_iou_y_contencion(box_a, box_b):
    ax1, ay1, ax2, ay2 = box_a
    bx1, by1, bx2, by2 = box_b
    area_a = max(0, ax2 - ax1) * max(0, ay2 - ay1)
    area_b = max(0, bx2 - bx1) * max(0, by2 - by1)

    ix1, iy1 = max(ax1, bx1), max(ay1, by1)
    ix2, iy2 = min(ax2, bx2), min(ay2, by2)
    inter = max(0, ix2 - ix1) * max(0, iy2 - iy1)
    union = area_a + area_b - inter

    iou = inter / (union + 1e-6)
    cont_b_en_a = inter / (area_b + 1e-6) if area_b > 0 else 0
    cont_a_en_b = inter / (area_a + 1e-6) if area_a > 0 else 0
    return iou, cont_b_en_a, cont_a_en_b

def post_procesar(outputs, orig_w, orig_h, conf_thresh=0.35, iou_thresh=0.45, cont_thresh=0.55):
    # NMS Híbrido: IoU clásico + Supresión por Contenimiento
    # Elimina falsos positivos donde una caja menor está dentro de otra mayor
    ...
    return keep

@app.post("/detectar")
async def detectar(foto: UploadFile = File(...), balones_declarados: int = Form(...)):
    img_bytes = await foto.read()
    tensor, orig_w, orig_h = preprocesar(img_bytes)
    outputs = session.run(None, {"images": tensor})
    cajas = post_procesar(outputs, orig_w, orig_h)
    detectados = len(cajas)

    return {
        "success": True,
        "detectados": detectados,
        "declarados": balones_declarados,
        "coincide": (detectados == balones_declarados),
        "cajas": cajas
    }
```

---

## 🐧 Despliegue Persistente en VPS Linux (Systemd)

Para asegurar que el microservicio inicie automáticamente al bootear el servidor y se reinicie en caso de error, se configura una unidad systemd en el VPS:

Archivo: [`detector_service/balon-detector.service`](file:///c:/xampp/htdocs/premia-surgas/detector_service/balon-detector.service)
```ini
# /etc/systemd/system/balon-detector.service
[Unit]
Description=Servicio de detección de balones GLP (FastAPI + ONNX)
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/opt/balon-detector
ExecStart=/opt/balon-detector/venv/bin/uvicorn balon_service:app --host 127.0.0.1 --port 8001 --workers 1
Restart=always
RestartSec=3
MemoryMax=600M
CPUQuota=150%

[Install]
WantedBy=multi-user.target
```

### Comandos de instalación en el servidor:
```bash
sudo cp detector_service/balon-detector.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now balon-detector
sudo systemctl status balon-detector
```

> **🔒 Garantía de Seguridad**: Al usar `--host 127.0.0.1`, el puerto `8001` solo es accesible dentro de la máquina (localhost). Ni internet ni usuarios externos pueden acceder directamente al servicio. Solo PHP puede consultarlo.

---

## 🐘 Integración y Consumo desde PHP

El archivo [`helpers/DetectorBalonesHelper.php`](file:///c:/xampp/htdocs/premia-surgas/helpers/DetectorBalonesHelper.php) encapsula la llamada HTTP interna vía cURL:

```php
// Uso en el controlador de evidencia (ej. AfiliadoController / ScanController):
require_once __DIR__ . '/../helpers/DetectorBalonesHelper.php';

$resultado = DetectorBalonesHelper::validar($_FILES['foto']['tmp_name'], $_POST['balones_declarados']);

if ($resultado['ok'] && $resultado['coincide']) {
    // 1. Aprobación automática: los balones detectados coinciden con los declarados
    asignarPuntos($puntoVentaId, $resultado['declarados']);
    echo json_encode([
        'aprobado' => true,
        'mensaje' => 'Evidencia validada correctamente por IA.'
    ]);
} else {
    // 2. Desvío a Bandeja de Revisión Manual (Sin auto-rechazo destructivo)
    marcarParaRevisionManual($puntoVentaId, $resultado, $_FILES['foto']);
    echo json_encode([
        'aprobado' => false,
        'pendiente_revision' => true,
        'mensaje' => 'La imagen pasará por revisión manual preventiva del administrador.'
    ]);
}
```

---

## 🛡️ ¿Por qué NO Rechazar Automáticamente? (Criterio de Negocio)

Si `detectados !== declarados`:
1. **NUNCA rechaces de inmediato los puntos al conductor o afiliado.**
2. En condiciones reales de trabajo de campo (luz tenue en almacén, balones apilados en camión con sombras fuertes, ángulos forzados), ningún modelo alcanza el 100.0% de fiabilidad en todos los disparos.
3. Rechazar de forma fulminante causa **fricción inmediata, desconfianza y quejas operativas** de conductores honestos.
4. **Solución**: La evidencia se registra en la tabla `evidencias_pendientes` con estado `EN_REVISION`. El administrador dispone de una pantalla donde visualiza la foto con las cajas del modelo y con **un solo clic aprueba o desestima** en 3 segundos.

---

## 📊 Huella de Recursos en VPS de 4 GB RAM

| Proceso | Consumo RAM en Reposo | Pico durante Inferencia |
|---|---|---|
| **Apache/Nginx + PHP-FPM** | Lo habitual de la app (~300 - 500 MB) | Sin cambios |
| **MariaDB** | ~400 - 800 MB | Sin cambios |
| **Microservicio Python (`balon_service`)** | **~120 - 180 MB** | **+50 - 80 MB** (temporal durante el forward pass) |
| **Total añadido por la IA** | **~200 - 260 MB** | — |

> Gracias al parámetro `MemoryMax=600M` en la configuración de `systemd`, el sistema operativo garantiza que bajo ninguna circunstancia el servicio de IA consumirá más RAM de la permitida, evitando cualquier riesgo para PHP o MariaDB.
