<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CoordinatorLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.coordinator.login');
    }

    public function login(Request $request)
    {
        // Log para debugging
        Log::info('Intento de login coordinador', [
            'email' => $request->email,
            'has_csrf' => $request->has('_token'),
            'csrf_token' => $request->input('_token'),
            'session_id' => session()->getId(),
            'user_agent' => $request->userAgent()
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::guard('coordinador')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            $coordinator = Auth::guard('coordinador')->user();
            
            // Log para debugging
            Log::info('Login coordinador exitoso', [
                'coordinator_id' => $coordinator->id,
                'coordinator_email' => $coordinator->email,
                'coordinator_name' => $coordinator->name,
                'redirect_to' => route('coordinador.dashboard')
            ]);
            
            // Verificar que el usuario está realmente autenticado
            if (Auth::guard('coordinador')->check()) {
                Log::info('Coordinador autenticado correctamente', [
                    'guard' => 'coordinador',
                    'user_id' => Auth::guard('coordinador')->id()
                ]);
            } else {
                Log::error('Coordinador no está autenticado después del login');
            }
            
            return redirect()->intended(route('coordinador.dashboard'));
        }

        Log::warning('Login coordinador fallido', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('auth.coordinator.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('coordinador')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Método de test para verificar el estado de autenticación.
     */
    public function testAuth(Request $request)
    {
        Log::info('Test de autenticación de coordinador', [
            'teacher_guard_check' => Auth::guard('teacher')->check(),
            'admin_guard_check' => Auth::guard('admin')->check(),
            'student_guard_check' => Auth::guard('student')->check(),
            'coordinador_guard_check' => Auth::guard('coordinador')->check(),
            'web_guard_check' => Auth::guard('web')->check(),
            'session_id' => $request->session()->getId(),
            'all_guards' => [
                'teacher' => Auth::guard('teacher')->user() ? Auth::guard('teacher')->user()->email : null,
                'admin' => Auth::guard('admin')->user() ? Auth::guard('admin')->user()->email : null,
                'student' => Auth::guard('student')->user() ? Auth::guard('student')->user()->email : null,
                'coordinador' => Auth::guard('coordinador')->user() ? Auth::guard('coordinador')->user()->email : null,
            ]
        ]);

        return response()->json([
            'teacher_authenticated' => Auth::guard('teacher')->check(),
            'admin_authenticated' => Auth::guard('admin')->check(),
            'student_authenticated' => Auth::guard('student')->check(),
            'coordinador_authenticated' => Auth::guard('coordinador')->check(),
            'web_authenticated' => Auth::guard('web')->check(),
        ]);
    }
}