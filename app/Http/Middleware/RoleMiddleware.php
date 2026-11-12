<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para restringir el acceso a rutas según el rol del usuario en el SIGR.
 */
class RoleMiddleware
{
    /**
     * Maneja una solicitud entrante verificando los roles permitidos.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->hasRole($roles)) {
            abort(403, 'No tiene permisos suficientes para acceder a este módulo del SIGR.');
        }

        return $next($request);
    }
}

