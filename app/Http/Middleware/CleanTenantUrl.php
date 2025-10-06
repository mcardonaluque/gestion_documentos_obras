<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\clear;

class CleanTenantUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener la ruta actual
        $path = $request->getRequestUri();
      
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
