<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;

    class TeacherLoginController extends Controller
    {
        /**
         * Muestra el formulario de login para profesores.
         */
        public function showLoginForm()
        {
            return view('auth.teacher.login');
        }

        /**
         * Procesa el intento de login.
         */
        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            if (Auth::guard('teacher')->attempt($credentials, $request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/profesor/dashboard');
            }

            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros de profesor.',
            ]);
        }

        /**
         * Cierra la sesión del profesor.
         */
        public function logout(Request $request)
        {
            Auth::guard('teacher')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }

        /**
         * Método de test para verificar el estado de autenticación.
         */
        public function testAuth(Request $request)
        {
            Log::info('Test de autenticación de profesor', [
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
    