<?php

declare(strict_types=1);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Verificar si la aplicación se encuentra en modo mantenimiento
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Registrar el cargador automático de Composer
require __DIR__ . '/../vendor/autoload.php';

// Inicializar Laravel y procesar la petición HTTP
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());

