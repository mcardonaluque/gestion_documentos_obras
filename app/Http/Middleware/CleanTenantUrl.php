<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\clear;

/**
 * Middleware que normaliza URLs con espacios codificados duplicados.
 *
 * Colapsa secuencias repetidas de `%20` en la URI y redirige a la URL
 * limpia cuando difiere de la original. Se registra en el middleware del
 * panel Obras (`ObrasPanelProvider`) y como tenantMiddleware del panel
 * Ayuntamientos (`AyuntamientosPanelProvider`), donde los slugs de
 * los equipos (ayuntamientos) pueden generar URLs con espacios.
 */
class CleanTenantUrl
{
    /**
     * Handle an incoming request.
     *
     * Limpia la URI de espacios codificados repetidos y redirige si cambia.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener la ruta actual
        $path = $request->getRequestUri();
        //dd($path);
        // Limpiar múltiples espacios codificados
        $cleanPath = preg_replace('/(%20)+/', '%20', $path);
       
        // Limpiar múltiples espacios normales
        //$cleanPath = preg_replace('/\s+/', '', urldecode($cleanPath));
     
        //$cleanPath = urlencode(trim($cleanPath));
        
        // Redirigir si la ruta está sucia
        if ($path !== $cleanPath) {
               
            return redirect()->to($cleanPath);
        }
        return $next($request);
    }
}
