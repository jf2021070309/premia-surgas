# 🏆 PremiaSurgas

**Sistema de Fidelización y Recompensas** para la distribuidora de gas Surgas. Plataforma web que gestiona puntos, canjes de premios y recargas de clientes a través de un programa de lealtad multi-rol.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-10.4-003545?logo=mariadb&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vuedotjs&logoColor=white)
![Railway](https://img.shields.io/badge/Deploy-Railway-0B0D0E?logo=railway&logoColor=white)

---

## 📋 Tabla de Contenidos

- [Arquitectura](#-arquitectura)
- [Roles del Sistema](#-roles-del-sistema)
- [Funcionalidades](#-funcionalidades)
  - [Autenticación y Seguridad](#1--autenticación-y-seguridad)
  - [Dashboard / Panel de Control](#2--dashboard--panel-de-control)
  - [Gestión de Clientes](#3--gestión-de-clientes)
  - [Sistema de Puntos](#4--sistema-de-puntos)
  - [Sistema de Incentivos (Metas)](#5--sistema-de-incentivos-metas)
  - [Tienda de Premios y Canjes](#6--tienda-de-premios-y-canjes)
  - [Sistema de Recargas](#7--sistema-de-recargas)
  - [Gestión de Productos/Premios](#8--gestión-de-productospremios)
  - [Gestión de Conductores](#9--gestión-de-conductores)
  - [Gestión de Afiliados Comerciales](#10--gestión-de-afiliados-comerciales)
  - [Tipos de Operaciones](#11--tipos-de-operaciones)
  - [Reportes y Auditoría](#12--reportes-y-auditoría)
  - [Configuración General](#13--configuración-general)
  - [Código QR del Cliente](#14--código-qr-del-cliente)
  - [Notificaciones en Tiempo Real](#15--notificaciones-en-tiempo-real)
- [Stack Tecnológico](#-stack-tecnológico)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Despliegue](#-despliegue)

---

## 🏗 Arquitectura

Patrón **MVC** (Model-View-Controller) con PHP puro y routing centralizado en `index.php`.

```
Cliente (Navegador)
    ↓
index.php (Front Controller + Router)
    ↓
Controller → Model → Base de Datos (MariaDB)
    ↓
View (PHP + Vue.js 3 + Chart.js)
```

---

## 👥 Roles del Sistema

| Rol | Descripción | Acceso |
|-----|-------------|--------|
| **Admin** | Acceso total: gestión de usuarios, premios, recargas, canjes, reportes, configuración | Panel completo |
| **Conductor** | Registra clientes, escanea QR, asigna puntos en campo | Panel limitado + Scan |
| **Afiliado** | Punto de venta asociado. Registra clientes y asigna puntos | Panel limitado + Scan |
| **Cliente** | Consulta puntos, canjea premios, recarga puntos, ve historial | Perfil + Tienda |

---

## ⚡ Funcionalidades

### 1. 🔐 Autenticación y Seguridad

- **Login unificado** — Un solo formulario para admin, conductor, afiliado y cliente
- **Doble tabla de autenticación** — Busca primero en `usuarios` (trabajadores), luego en `clientes` (DNI + contraseña)
- **Sesión única forzada** — Solo una sesión activa por usuario (al iniciar sesión en otro dispositivo, la anterior se invalida automáticamente)
- **Detección de sesión expirada** — En peticiones AJAX devuelve `401 + JSON`, en navegación redirige al login
- **Auto-registro público** de clientes vía formulario web (`/registro`)
- **Hash SHA-256** para contraseñas
- **Auditoría de login/logout** — Cada inicio y cierre de sesión queda registrado

### 2. 📊 Dashboard / Panel de Control

#### Vista Admin
- **4 KPIs principales**: Usuarios totales, Canjes del día, Puntos dados hoy, Recargas pendientes
- **Gráfico de barras** — Top 10 premios más canjeados (Chart.js, barras horizontales)
- **Gráfico donut** — Distribución de canjes Full (100% puntos) vs Mix (puntos + dinero)
- **Ranking de canjeadores** — Top 10 clientes con más canjes, con avatar de iniciales y badge de líder
- **Lista de solicitudes de recarga pendientes** con acceso directo a gestión
- **Lista de últimos canjes** con modal de detalle (cliente, premio, puntos usados, monto)

#### Vista Conductor / Afiliado
- **Banner de bienvenida personalizado** con nombre del usuario
- **3 KPIs animados** con efecto counter: Puntos entregados hoy, Total histórico, Clientes atendidos
- **Historial de actividad reciente** — Últimas 5 ventas realizadas con nombre del cliente, fecha en español y puntos asignados
- **Animaciones premium**: `slideFadeDown`, hover effects con `translateY`, cards con sombras dinámicas

### 3. 👤 Gestión de Clientes

- **Registro rápido** desde el panel del conductor/afiliado (`/clientes/nuevo`)
- **3 tipos de cliente**: Normal (persona natural con DNI), Restaurante (empresa con RUC), Punto de Venta (empresa con RUC)
- **Consulta de DNI automática** — Integración con API `apis.net.pe` para autocompletar nombre al ingresar 8 dígitos
- **Consulta de RUC automática** — Integración con API `apis.net.pe` para obtener razón social y dirección
- **Validación de duplicados** — Por DNI, RUC o celular; si el cliente ya existe, devuelve su código
- **Generación automática de código** — Formato `CLI-000001`, `CLI-000002`, etc.
- **Token de seguridad** — HMAC-SHA256 único por cliente para acceso vía QR
- **Directorio completo** (`/clientes/lista`) — Lista de todos los clientes con búsqueda
- **Edición de clientes** — Solo admin puede modificar datos (con tracking de cambios)
- **Activar / Inactivar clientes** — Soft-delete con campo `estado`
- **Impresión de QR** — Vista optimizada para imprimir el código QR del cliente

### 4. ⭐ Sistema de Puntos

- **Asignación manual por operaciones** — El conductor/afiliado escanea o busca al cliente, selecciona operaciones (gas normal, gas premium, accesorio) y cantidad
- **Búsqueda flexible** — Acepta código QR (`CLI-000001`), DNI (8 dígitos), RUC (11 dígitos) o ID numérico
- **Tipos de operación configurables**: Recarga gas Normal (6 pts), Recarga gas Premium (10 pts), Accesorio/Otros (2 pts)
- **Registro detallado por ítem** — Cada venta guarda un desglose en `venta_detalles` (nombre, cantidad, puntos unitarios, subtotal)
- **Resumen tipo factura** — Antes de confirmar, se muestra un desglose con bullets (•), línea separadora (──────────) y TOTAL
- **Suma automática** al saldo del cliente tras confirmar
- **Auditoría completa** — Cada carga queda registrada con el detalle exacto

### 5. 🎯 Sistema de Incentivos (Metas)

- **Independiente a los puntos** — Sistema paralelo diseñado para fidelizar negocios B2B (Restaurantes, Puntos de Venta) y clientes top.
- **Reglas Configurables** — El administrador define metas de volumen de compras (ej. "Comprar 10 balones al mes").
- **Tipos de Recompensa** — Descuento porcentual (ej. 50%), monto fijo en soles, o un vale de producto.
- **Evaluación Automática (*Hook*)** — Cada vez que se registra una venta, el sistema evalúa silenciosamente si el cliente alcanzó la meta del periodo.
- **Generación de Vales Digitales** — Al cumplir la meta, se emite automáticamente un vale único (`VALE-YYYYMMDD-XXXX`) con una vigencia definida en días.
- **Panel Administrativo** — Dashboard dedicado para crear o editar reglas, y consultar o marcar los vales redimidos físicamente por el cliente.
- **Vista del Cliente** — Pestaña interactiva en el perfil que muestra una barra de progreso en vivo ("Llevas 7/10 ops") y el listado de vales disponibles para su redención inmediata.

### 6. 🛍 Tienda de Premios y Canjes

- **Catálogo visual por niveles** — Los premios se organizan en 4 niveles según puntos:
  - Nivel Bajo (≤250 pts): Tazas, vasos, platos
  - Nivel Medio (≤3,000 pts): Licuadoras, ollas, cocinas
  - Nivel Alto (≤10,000 pts): Televisores, refrigeradoras
  - Nivel VIP (>10,000 pts): Laptops, iPhones
- **3 modalidades de canje**:
  - **Canje Full** — 100% con puntos
  - **Canje Híbrido (Yape/Efectivo)** — Parte en puntos + parte en dinero efectivo
  - **Canje Híbrido (Depósito BBVA)** — Parte en puntos + parte con transferencia (requiere comprobante)
- **Slider dinámico** — El cliente ajusta cuántos puntos usar vs. cuánto pagar en dinero
- **Validación de stock** — Solo canjea si hay unidades disponibles
- **Subida de comprobante a ImgBB** — Para pagos por depósito, el comprobante se sube a la nube
- **Historial de canjes del cliente** (`/tienda/historial`) con estados visuales

#### Estados de un Canje
| Estado | Descripción |
|--------|-------------|
| `pendiente` | Solicitud recibida, esperando entrega |
| `entregado` | Premio ya entregado al cliente |
| `pago_pendiente` | Comprobante enviado, esperando validación |
| `pago_aprobado` | Pago verificado por admin |
| `pago_rechazado` | Comprobante no válido |
| `cancelado` | Canje cancelado |

### 7. 💳 Sistema de Recargas

#### Lado Cliente
- **Solicitud de recarga** — El cliente selecciona un paquete de puntos, adjunta comprobante de pago (foto/captura) y envía la solicitud
- **Subida de comprobantes** — Los archivos se guardan en `assets/uploads/comprobantes/`
- **Verificación de pendientes** — API que consulta si el cliente tiene recargas en espera

#### Lado Admin
- **Panel de gestión** (`/recargas-admin`) — Lista de todas las recargas pendientes con imagen del comprobante
- **Aprobar o rechazar** — Al aprobar, los puntos se suman automáticamente al saldo del cliente
- **Historial completo** — Todas las recargas con estado, fecha de validación y quién la validó
- **Configuración de QR de Yape** — Subir/actualizar el código QR y nombre del titular para pagos

### 8. 📦 Gestión de Productos/Premios

- **CRUD completo** de premios (`/productos`)
- **Campos**: nombre, descripción, puntos requeridos, stock, imagen, estado (activo/inactivo)
- **Subida de imágenes** — Las imágenes de premios se almacenan en `assets/premios/`
- **Activar/Inactivar** premios sin eliminarlos
- **Eliminación definitiva** con auditoría
- **Solo acceso admin**

### 9. 🚛 Gestión de Conductores

- **CRUD completo** (`/conductores`)
- **Campos**: nombre, usuario de acceso, contraseña, departamento (Tacna, Moquegua, Arequipa, Ilo), estado
- **Historial personal** (`/conductores/mi-historial`) — Cada conductor ve sus asignaciones de puntos con:
  - Búsqueda por nombre/DNI del cliente
  - Filtro por rango de fechas
  - Paginación (10 registros por página)
  - Total de puntos filtrados
  - Desglose de ítems por venta (expandible)
- **Tracking de cambios** — Cada edición registra qué campos se modificaron (antes vs. después)
- **Restricción de eliminación** — No se puede eliminar un conductor con ventas vinculadas

### 10. 🤝 Gestión de Afiliados Comerciales

- **CRUD completo** (`/afiliados`) — Misma estructura que conductores pero con rol `afiliado`
- **Los afiliados pueden**: Registrar clientes nuevos, asignar puntos, ver su historial de asignaciones
- **Historial paginado** con filtros de búsqueda y fechas
- **Se crean como usuarios con rol `afiliado`** en la tabla `usuarios`

### 11. ⚙️ Tipos de Operaciones

- **Gestión de reglas de puntos** (`/operaciones`) — Define qué operaciones generan puntos y cuántos
- **CRUD completo**: crear, editar, activar/inactivar, eliminar reglas
- **Reglas actuales**:
  - Recarga gas Normal → 6 puntos
  - Recarga gas Premium → 10 puntos
  - Accesorio / Otros → 2 puntos
- **Protección referencial** — No se puede eliminar una regla con historial asociado

### 12. 📈 Reportes y Auditoría

#### Reportes (`/reportes`)
- Resumen general del sistema
- Ventas por conductor
- Canjes recientes
- Premios más populares
- Gráficos de ventas y canjes de los últimos 15 días

#### Auditoría (`/reporte/auditoria`)
- **Log completo** de todas las acciones del sistema (hasta 500 registros)
- **Datos registrados**: usuario, acción, descripción, módulo, IP, user agent, fecha/hora
- **Metadata JSON** — Los cambios de campos se almacenan como JSON (`{campo: {ant: "antes", des: "después"}}`)
- **Categorías de acciones**: INICIO_SESION, CIERRE_SESION, REGISTRO_CLIENTE, CARGA_PUNTOS, SOLICITUD_CANJE, ESTADO_CANJE, ACTUALIZAR_CLIENTE, NUEVO_CONDUCTOR, MODERAR_RECARGA, etc.
- **Carga JSON asíncrona** para la tabla de auditoría

### 13. 🔧 Configuración General

- **Panel unificado** (`/ajustes`) que agrupa:
  - Gestión de tipos de operaciones (reglas de puntos)
  - Lista de premios con acceso a edición
  - Gestión de conductores
  - Configuración del monto por punto (equivalencia en Soles para canjes mixtos)
- **Monto por punto** — Valor configurable que define cuánto vale cada punto en Soles (ej: S/ 0.10 por punto)

### 14. 📱 Código QR del Cliente

- **Generación automática** al registrar un cliente
- **URL codificada** — `{BASE_URL}/scan?c={codigo}&t={token}` embebida en el QR
- **Doble uso del QR**:
  - **Cliente lo escanea** → Ve su perfil con puntos, historial y canjes
  - **Conductor lo escanea** → Abre la interfaz para asignar puntos
- **Vista de impresión** — Página optimizada para imprimir tarjetas con QR

### 15. 🔔 Notificaciones en Tiempo Real

- **Polling periódico** — El panel admin consulta cada pocos segundos por nuevas solicitudes
- **Endpoint API** (`/panel/live-notifications`) — Devuelve JSON con recargas pendientes y canjes pendientes
- **Badge en la campana** del header mostrando cantidad de pendientes
- **Auto-actualización** del contador sin recargar la página

---

## 🛠 Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| **Backend** | PHP 8.2 (sin frameworks) |
| **Base de Datos** | MariaDB 10.4 / MySQL |
| **Frontend** | HTML5, CSS3 (Vanilla), JavaScript ES6+ |
| **Reactivo** | Vue.js 3 (CDN) — para modales y componentes interactivos |
| **Gráficos** | Chart.js — barras, donut, líneas |
| **Iconos** | Boxicons |
| **Tipografía** | Inter (Google Fonts) |
| **Alertas** | SweetAlert2 |
| **Servidor Local** | XAMPP (Apache) |
| **Producción** | Docker + Railway |
| **Imágenes Cloud** | ImgBB API (comprobantes de canje) |
| **API Datos** | apis.net.pe (consulta DNI/RUC peruano) |

---

## 📁 Estructura del Proyecto

```
premia-surgas/
├── assets/
│   ├── css/           # Estilos globales (admin-layout.css)
│   ├── js/            # Scripts globales (session_check.js)
│   ├── premios/       # Imágenes de premios
│   └── uploads/       # Comprobantes y QR subidos
├── config/
│   ├── config.php     # Configuración global, constantes, helpers
│   └── Database.php   # Conexión PDO singleton
├── controllers/
│   ├── AuthController.php          # Login, logout, check session
│   ├── PanelController.php         # Dashboard con métricas
│   ├── ClienteController.php       # CRUD clientes + consulta DNI/RUC
│   ├── ScanController.php          # Escaneo QR y asignación de puntos
│   ├── TiendaController.php        # Catálogo, canjes y recargas
│   ├── ProductoController.php      # CRUD premios
│   ├── ConductorController.php     # CRUD conductores
│   ├── AfiliadoController.php        # CRUD afiliados comerciales
│   ├── OperacionController.php     # CRUD tipos de operaciones
│   ├── IncentivoController.php     # Gestión de incentivos y metas
│   ├── CanjeAdminController.php    # Gestión de entregas de canjes
│   ├── RecargaAdminController.php  # Moderación de recargas
│   ├── AjustesController.php       # Configuración general
│   ├── ReporteController.php       # Reportes y auditoría
│   └── QrController.php            # Generación de QR
├── models/
│   ├── ClienteModel.php
│   ├── UsuarioModel.php
│   ├── VentaModel.php
│   ├── CanjeModel.php
│   ├── PremioModel.php
│   ├── RecargaModel.php
│   ├── TipoOperacionModel.php
│   ├── IncentivoModel.php
│   ├── ConfiguracionModel.php
│   ├── AuditoriaModel.php
│   └── ReporteModel.php
├── views/
│   ├── login.php                   # Pantalla de login
│   ├── panel.php                   # Dashboard principal
│   ├── tienda.php                  # Catálogo de premios (cliente)
│   ├── tienda_historial.php        # Historial de canjes (cliente)
│   ├── canjes_admin.php            # Gestión de canjes (admin)
│   ├── recargas_admin.php          # Gestión de recargas (admin)
│   ├── scan/                       # Vistas de escaneo QR y perfil
│   ├── clientes/                   # Vistas de gestión de clientes
│   ├── productos/                  # Vistas de gestión de premios
│   ├── conductores/                # Vistas de gestión de conductores
│   ├── afiliados/                    # Vistas de gestión de afiliados
│   ├── incentivos/                 # Vistas del sistema de incentivos
│   ├── operaciones/                # Vistas de tipos de operaciones
│   ├── ajustes/                    # Vistas de configuración
│   ├── reportes/                   # Vistas de reportes y auditoría
│   └── partials/                   # Sidebar, Header reutilizables
├── helpers/
│   └── UploadHelper.php            # Subida de imágenes a ImgBB
├── index.php                       # Front Controller + Router
├── router.php                      # Router para PHP built-in server
├── .htaccess                       # Rewrite rules (Apache/XAMPP)
├── Dockerfile                      # Imagen Docker para Railway
├── database.sql                    # Schema + datos (local)
└── database_railway.sql            # Schema + datos (producción)
```

---

## 🚀 Despliegue

### Local (XAMPP)
1. Clonar el repositorio en `htdocs/premia-surgas/`
2. Importar `database.sql` en MariaDB (base de datos: `surgas`)
3. Acceder a `http://localhost/premia-surgas/`

### Producción (Railway)
- Despliegue automático vía Docker
- Variable de entorno `MYSQL_URL` para conexión a la base de datos
- El `Dockerfile` usa `php:8.2-cli` con el servidor built-in de PHP
- Puerto configurable vía `$PORT` (default: 8080)

### Credenciales por Defecto
| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Admin | `admin` | `123456` |

---

## 📊 Base de Datos

### Tablas Principales
| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Administradores, conductores y afiliados |
| `clientes` | Clientes del programa de fidelización |
| `premios` | Catálogo de premios disponibles |
| `ventas` | Registro de asignaciones de puntos |
| `venta_detalles` | Desglose de ítems por venta |
| `canjes` | Solicitudes de canje de premios |
| `recargas` | Solicitudes de recarga de puntos |
| `tipos_operaciones` | Reglas de puntos por tipo de operación |
| `incentivos_reglas` | Configuración de metas B2B y premios |
| `incentivos_vales` | Vales digitales generados automáticamente |
| `configuraciones` | Parámetros globales del sistema |
| `auditoria` | Log de todas las acciones del sistema |

---

> **PremiaSurgas** — Desarrollado para FUTURE STORE EIRL · Surgas Distribuidora de Gas
