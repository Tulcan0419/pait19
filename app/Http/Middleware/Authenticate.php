<?php

    namespace App\Http\Middleware;

    use Illuminate\Auth\Middleware\Authenticate as Middleware;
    use Illuminate\Http\Request;

    class Authenticate extends Middleware
    {
        /**
         * Get the path the user should be redirected to when they are not authenticated.
         */
        protected function redirectTo(Request $request): ?string
        {
            if (! $request->expectsJson()) {
                if ($request->routeIs('estudiante.*')) {
                    return route('estudiante.login');
                }
                if ($request->routeIs('profesor.*')) { // Añadido para profesores
                    return route('profesor.login');
                }
                // Añadido para coordinadores
                if ($request->routeIs('coordinador.*')) {
                    return route('coordinador.login');
                }
                // Puedes añadir más condiciones para otros guardias si los tienes
                if ($request->routeIs('admin.*')) {
                    return route('admin.login');
                }
                return route('admin.login'); // Ruta de login por defecto para el guardia 'web'
            }
            return null;
        }
    }
    