<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentSemesterRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $student = Auth::guard('student')->user();
        
        if ($student && $student->semester < 3) {
            return redirect()->back()->with('error', 'Las Prácticas Preprofesionales están disponibles únicamente para estudiantes del tercer semestre en adelante. Tu semestre actual es: ' . $student->semester . '° semestre.');
        }
        
        return $next($request);
    }
}
