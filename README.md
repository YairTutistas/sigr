# Sistema Integral de Gestión de Restaurante (SIGR)

![Versión](https://img.shields.io/badge/versi%C3%B3n-v1.0.0%20(LB--01)-orange)
![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2013-FF2D20)
![Inertia.js](https://img.shields.io/badge/Inertia.js-v2.0-9553E9)
![Vue.js](https://img.shields.io/badge/Vue.js-v3.5-4FC08D)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v3.4%2F4.0-38B2AC)
![Licencia](https://img.shields.io/badge/Licencia-MIT-blue)

## Descripción del Proyecto

El **Sistema Integral de Gestión de Restaurante (SIGR)** es una plataforma web monolítica moderna (SPA impulsada por servidor) diseñada para centralizar y automatizar la operación gastronómica y administrativa de un restaurante.

Esta **Línea Base v1.0.0 (LB-01)** establece la arquitectura fundamental utilizando **Laravel**, **Inertia.js 2**, **Vue 3** y **Tailwind CSS**, cubriendo los siguientes módulos críticos:

- **Autenticación y Control de Acceso por Roles (RBAC):** Soporte para perfiles de `admin`, `mesero`, `cajero`, `cocina` y `cliente`.
- **Gestión de Menú y Catálogo Gastronómico:** CRUD completo de categorías (`Category`) y platos/productos (`Dish`) con control de costos, precios de venta y disponibilidad en tiempo real.
- **Gestión de Salón y Reservaciones:** Administración de mesas físicas (`Table`) con estados dinámicos (`disponible`, `ocupada`, `reservada`) y motor de verificación de disponibilidad horaria (`Reservation`).
- **Toma de Comandas y Flujo de Cocina:** Creación transaccional de órdenes (`Order` y `OrderItem`) con seguimiento de estados (`pendiente`, `en_preparacion`, `servido`, `pagado`).
- **Control de Caja Registradora:** Apertura, seguimiento de ventas por turno y arqueo/cierre de caja (`CashRegister`).
- **Facturación y Comprobantes:** Generación de facturas/comprobantes (`Receipt`) con desglose de subtotal, impuestos y cálculo de propina voluntaria (por porcentaje o monto fijo).

---

## Requisitos del Sistema

Asegúrese de contar con el siguiente entorno de desarrollo antes de iniciar la instalación:

- **PHP:** `>= 8.3` o `8.4` (con extensiones `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- **Composer:** `>= 2.7`
- **Node.js:** `>= 20.x LTS` y **NPM** `>= 10.x`
- **Servidor de Base de Datos:** **MySQL** `>= 8.0` (o MariaDB `>= 10.6`)

---

## Instalación y Configuración Paso a Paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/YairTutistas/sigr.git
cd sigr
```

### 2. Instalar dependencias de Backend y Frontend

```bash
composer install
npm install
```

### 3. Configurar las variables de entorno

Copie el archivo de ejemplo `.env.example` a `.env` y genere la llave de cifrado de la aplicación:

```bash
cp .env.example .env
php artisan key:generate
```

Configure las credenciales de conexión a su base de datos MySQL en el archivo `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigr_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Ejecutar migraciones y carga de datos iniciales (Seeders)

Ejecute las migraciones junto con el `DatabaseSeeder` para crear todas las tablas y poblar los usuarios de prueba, las 5 categorías, los 15 platos iniciales y las 10 mesas configuradas:

```bash
php artisan migrate --seed
```

### 5. Arranque del servidor de desarrollo

Puede iniciar simultáneamente el servidor HTTP de Laravel y el servidor de compilación en caliente (HMR) de Vite ejecutando:

```bash
composer run dev
```

O bien, en dos terminales separadas:

```bash
# Terminal 1: Servidor Backend Laravel (http://localhost:8000)
php artisan serve

# Terminal 2: Compilador Frontend Vite + Vue 3
npm run dev
```

---

## Credenciales de Acceso Iniciales (Seeders)

Tras ejecutar `php artisan migrate --seed`, tendrá disponibles las siguientes cuentas de prueba:

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@sigr.local` | `password` |
| **Mesero** | `mesero@sigr.local` | `password` |
| **Cajero** | `cajero@sigr.local` | `password` |

---

## Estructura de la Arquitectura (LB-01)

```text
sigr/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Autenticación y sesiones
│   │   │   ├── CashRegisterController.php  # Apertura y cierre de caja
│   │   │   ├── MenuController.php          # CRUD de categorías y platos
│   │   │   ├── OrderController.php         # Comandas y estados de pedido
│   │   │   ├── ReceiptController.php       # Comprobantes y propina voluntaria
│   │   │   └── ReservationController.php   # Reservas y disponibilidad de mesas
│   │   └── Middleware/
│   │       ├── HandleInertiaRequests.php   # Estado compartido con Inertia 2
│   │       └── RoleMiddleware.php          # Protección de rutas por rol
│   └── Models/
│       ├── CashRegister.php
│       ├── Category.php
│       ├── Dish.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Receipt.php
│       ├── Reservation.php
│       ├── Table.php
│       └── User.php
├── database/
│   ├── migrations/                         # 9 migraciones relacionales MySQL
│   └── seeders/
│       └── DatabaseSeeder.php              # Datos semilla de la Línea Base
├── resources/
│   ├── css/app.css                         # Directivas de Tailwind CSS
│   ├── js/
│   │   ├── app.js                          # Punto de entrada Vue 3 + Inertia 2
│   │   └── Pages/                          # Vistas reactivas en Vue 3
│   └── views/app.blade.php                 # Plantilla raíz de Inertia
└── routes/
    └── web.php                             # Definición de rutas del sistema
```

---

## Licencia

Este proyecto está licenciado bajo la **Licencia MIT**. Consulte el archivo [LICENSE](./LICENSE) para más detalles.  
© 2026 **Yair Andres Tutistas Vidal**.

