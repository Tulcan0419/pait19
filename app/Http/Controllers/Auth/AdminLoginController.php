<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Clear any other guard sessions to prevent conflicts
            Auth::guard('coordinador')->logout();
            Auth::guard('teacher')->logout();
            Auth::guard('student')->logout();
            
            Log::info('AdminLoginController: Admin login successful, redirecting to /admin/dashboard');
            
            // Force redirect to admin dashboard
            return redirect('/admin/dashboard');
        }
        
        Log::info('AdminLoginController: Admin login failed');
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden o no tienes acceso de administrador.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}