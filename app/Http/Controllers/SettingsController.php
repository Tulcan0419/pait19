<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Coordinator;
use App\Models\Admin;

class SettingsController extends Controller
{
    /**
     * Show settings page for students
     */
    public function studentSettings()
    {
        $student = Auth::guard('student')->user();
        return view('settings.student', compact('student'));
    }

    /**
     * Show settings page for teachers
     */
    public function teacherSettings()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('settings.teacher', compact('teacher'));
    }

    /**
     * Show settings page for coordinators
     */
    public function coordinatorSettings()
    {
        $coordinator = Auth::guard('coordinador')->user();
        return view('settings.coordinator', compact('coordinator'));
    }

    /**
     * Show settings page for admins
     */
    public function adminSettings()
    {
        $admin = Auth::guard('admin')->user();
        return view('settings.admin', compact('admin'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:' . $this->getTableName($userType) . ',email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!password_verify($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Get user type based on the authenticated user
     */
    private function getUserType($user)
    {
        if ($user instanceof Student) {
            return 'student';
        } elseif ($user instanceof Teacher) {
            return 'teacher';
        } elseif ($user instanceof Coordinator) {
            return 'coordinator';
        } elseif ($user instanceof Admin) {
            return 'admin';
        }
        
        return 'unknown';
    }

    /**
     * Get table name based on user type
     */
    private function getTableName($userType)
    {
        switch ($userType) {
            case 'student':
                return 'students';
            case 'teacher':
                return 'teachers';
            case 'coordinator':
                return 'coordinators';
            case 'admin':
                return 'admins';
            default:
                return 'users';
        }
    }

    /**
     * Get settings route based on user type
     */
    public function getSettingsRoute($userType)
    {
        switch ($userType) {
            case 'student':
                return route('estudiante.settings');
            case 'teacher':
                return route('profesor.settings');
            case 'coordinator':
                return route('coordinador.settings');
            case 'admin':
                return route('admin.settings');
            default:
                return route('welcome');
        }
    }
}
