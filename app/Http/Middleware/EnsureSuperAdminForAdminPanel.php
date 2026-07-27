<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminForAdminPanel
{
    /**
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
