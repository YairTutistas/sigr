# Registro de Cambios (Changelog)

Todos los cambios notables en el proyecto **Sistema Integral de Gestión de Restaurante (SIGR)** serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/), y este proyecto se adhiere al [Versionamiento Semántico (SemVer)](https://semver.org/lang/es/).

---

## [1.0.0] - 2026-11-11 — Línea Base Inicial (LB-01)

### Añadido (Added)

- **Arquitectura Base del Proyecto:**
  - Configuración inicial del proyecto monolítico moderno con **Laravel**, **Inertia.js 2**, **Vue 3** y **Tailwind CSS**.
  - Tipado estricto (`declare(strict_types=1);`) y cumplimiento del estándar de codificación **PSR-12** en todos los módulos PHP.
- **Esquema de Base de Datos Relacional (MySQL) y Modelos Eloquent:**
  - `User`: Gestión de usuarios con soporte de roles (`admin`, `mesero`, `cajero`, `cocina`, `cliente`).
  - `Category`: Categorías del menú gastronómico con `slug` único y estado activo/inactivo.
  - `Dish`: Catálogo de platos y productos con precio de venta, costo unitario, disponibilidad e imagen.
  - `Table`: Control de mesas del restaurante con capacidad y estados operativos (`disponible`, `ocupada`, `reservada`).
  - `Reservation`: Registro de reservaciones de clientes vinculadas a mesas y fechas específicas (`pendiente`, `confirmada`, `cancelada`).
  - `Order` y `OrderItem`: Comandas de mesas con detalle de platos, cantidades, precios unitarios, notas de preparación y flujo de estados (`pendiente`, `en_preparacion`, `servido`, `pagado`).
  - `CashRegister`: Sesiones de apertura y cierre de caja registradora con arqueo de ventas por turno (`abierta`, `cerrada`).
  - `Receipt`: Emisión de comprobantes/facturas con numeración única, subtotal, impuestos, propina voluntaria y total facturado.
- **Controladores y Lógica de Negocio:**
  - `AuthController`: Flujo de inicio de sesión, registro de usuarios y cierre seguro de sesión compatible con Fortify e Inertia.js.
  - `MenuController`: Operaciones CRUD completas para categorías y platos del menú con filtros de búsqueda.
  - `OrderController`: Creación transaccional de comandas, cálculo automático de totales y actualización del estado del pedido y de la mesa.
  - `ReservationController`: Verificación de disponibilidad de mesas por ventana horaria y capacidad, y registro de reservas.
  - `CashRegisterController`: Apertura de caja con monto base y cierre con consolidación automática de ventas pagadas.
  - `ReceiptController`: Generación de comprobante fiscal/interno, cálculo de propina voluntaria (porcentaje o monto fijo) y liberación automática de la mesa.
- **Datos Semilla (`DatabaseSeeder`):**
  - Creación de 3 usuarios operativos base: Administrador (`admin@sigr.local`), Mesero (`mesero@sigr.local`) y Cajero (`cajero@sigr.local`).
  - Creación de 5 categorías gastronómicas (`Entradas`, `Platos Fuertes`, `Bebidas`, `Postres`, `Licores`).
  - Creación de 15 platos detallados con descripciones, costos y precios reales.
  - Configuración inicial de 10 mesas en el restaurante con distintas capacidades.
- **Documentación y Licenciamiento:**
  - Incorporación de `README.md`, `CHANGELOG.md` y licencia `LICENSE` (MIT).

