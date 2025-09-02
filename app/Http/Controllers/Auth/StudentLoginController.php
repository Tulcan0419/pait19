<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    /**
     * Muestra el formulario de login para estudiantes
     */
    public function showLoginForm()
    {
        return view('auth.student.login');
    }

    /**
     * Procesa el intento de login
     */
    public function login(Request $request)
    {
        // Validación
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Intento de autenticación con el guardia 'student'
        if (Auth::guard('student')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Obtener el estudiante autenticado para determinar su carrera
            $student = Auth::guard('student')->user();

            // Redirigir al dashboard específico de la carrera del estudiante
            // La ruta 'estudiante.dashboard.career' se definirá en web.php
            return redirect()->intended(route('estudiante.dashboard.career', ['career' => $student->career]));
        }

        // Si la autenticación falla
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cierra la sesión del estudiante
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
