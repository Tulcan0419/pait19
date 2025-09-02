<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        // Permitir acceso a la ruta raíz (welcome.blade.php) incluso para usuarios autenticados
        if ($request->is('/')) {
            return $next($request);
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Log para debugging
                Log::info('RedirectIfAuthenticated: Usuario autenticado detectado', [
                    'guard' => $guard,
                    'route' => $request->route()->getName(),
                    'url' => $request->url(),
                    'teacher_check' => Auth::guard('teacher')->check(),
                    'admin_check' => Auth::guard('admin')->check(),
                    'student_check' => Auth::guard('student')->check(),
                    'coordinador_check' => Auth::guard('coordinador')->check(),
                ]);

                // Redirigir según el guard específico que está autenticado
                if ($guard === 'admin') {
                    Log::info('RedirectIfAuthenticated: Redirigiendo admin a /admin/dashboard');
                    return redirect('/admin/dashboard');
                }
                if ($guard === 'teacher') {
                    Log::info('RedirectIfAuthenticated: Redirigiendo teacher a /profesor/dashboard');
                    return redirect('/profesor/dashboard');
                }
                if ($guard === 'student') {
                    Log::info('RedirectIfAuthenticated: Redirigiendo student a /estudiante/dashboard');
                    return redirect('/estudiante/dashboard');
                }
                if ($guard === 'coordinador') {
                    Log::info('RedirectIfAuthenticated: Redirigiendo coordinador a /coordinador/dashboard');
                    return redirect('/coordinador/dashboard');
                }
                
                // Si no hay un guard específico, verificar todos los guards y redirigir según el primero autenticado
                if (Auth::guard('admin')->check()) {
                    Log::info('RedirectIfAuthenticated: Redirigiendo admin (verificación general) a /admin/dashboard');
                    return redirect('/admin/dashboard');
                }
                if (Auth::guard('teacher')->check()) {
                    Log::info('RedirectIfAuthenticated: Redirigiendo teacher (verificación general) a /profesor/dashboard');
                    return redirect('/profesor/dashboard');
                }
                if (Auth::guard('student')->check()) {
                    Log::info('RedirectIfAuthenticated: Redirigiendo student (verificación general) a /estudiante/dashboard');
                    return redirect('/estudiante/dashboard');
                }
                if (Auth::guard('coordinador')->check()) {
                    Log::info('RedirectIfAuthenticated: Redirigiendo coordinador (verificación general) a /coordinador/dashboard');
                    return redirect('/coordinador/dashboard');
                }
                
                Log::info('RedirectIfAuthenticated: Redirigiendo a RouteServiceProvider::HOME');
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
