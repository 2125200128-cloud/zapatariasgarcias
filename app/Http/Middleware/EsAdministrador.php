<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdministrador
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->session()->get('api_user');
        $esAdmin = is_array($usuario) && ($usuario['esAdministrador'] ?? false);

        if (!$esAdmin) {
            abort(403, 'Solo un administrador puede acceder a esta sección.');
        }

        return $next($request);
    }
}