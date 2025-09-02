<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentTutorAssignment;

class TeacherProfessionalPracticesAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $teacher = Auth::guard('teacher')->user();
        
        if (!$teacher) {
            return redirect()->route('profesor.login');
        }

        // Permitir acceso a todos los profesores autenticados
        // El controlador manejará la lógica de mostrar contenido o mensaje
        return $next($request);
    }
} 