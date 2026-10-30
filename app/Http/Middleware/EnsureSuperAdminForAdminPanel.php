<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que restringe el acceso al panel de administración de Filament.
 *
 * Solo permite continuar a usuarios autenticados con el rol `super_admin`
 * (Spatie Permission); en caso contrario aborta con 403. Se registra como
 * authMiddleware del panel Admin en `AdminPanelProvider`.
 */
class EnsureSuperAdminForAdminPanel
{
    /**
     * Comprueba que el usuario autenticado tenga el rol `super_admin`
     * antes de dejar pasar la petición al panel de administración.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('web')->user();

        if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole('super_admin')) {
            abort(403, 'No tiene permisos para acceder al panel de administracion.');
        }

        return $next($request);
    }
}
