<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Coordinator;
use App\Models\Admin;

class ProfilePhotoController extends Controller
{
    /**
     * Show the form to upload profile photo
     */
    public function showUploadForm()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Get the correct routes based on user type
        $routes = $this->getRoutesForUserType($userType);
        
        return view('profile.upload-photo', compact('user', 'userType', 'routes'));
    }

    /**
     * Upload profile photo
     */
    public function upload(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $userType = $this->getUserType($user);

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        // Store new photo
        $photoName = time() . '_' . $user->id . '.' . $request->file('profile_photo')->getClientOriginalExtension();
        $request->file('profile_photo')->storeAs('profile-photos', $photoName, 'public');

        // Update user record
        $user->profile_photo = $photoName;
        $user->save();

        return redirect()->back()->with('success', 'Foto de perfil actualizada exitosamente.');
    }

    /**
     * Remove profile photo
     */
    public function remove()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto de perfil eliminada exitosamente.');
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
     * Get routes for specific user type
     */
    private function getRoutesForUserType($userType)
    {
        switch ($userType) {
            case 'student':
                return [
                    'upload' => route('estudiante.profile.photo.upload'),
                    'remove' => route('estudiante.profile.photo.remove'),
                    'dashboard' => route('estudiante.dashboard')
                ];
            case 'teacher':
                return [
                    'upload' => route('profesor.profile.photo.upload'),
                    'remove' => route('profesor.profile.photo.remove'),
                    'dashboard' => route('profesor.dashboard')
                ];
            case 'coordinator':
                return [
                    'upload' => route('coordinador.profile.photo.upload'),
                    'remove' => route('coordinador.profile.photo.remove'),
                    'dashboard' => route('coordinador.dashboard')
                ];
            case 'admin':
                return [
                    'upload' => route('admin.profile.photo.upload'),
                    'remove' => route('admin.profile.photo.remove'),
                    'dashboard' => route('admin.dashboard')
                ];
            default:
                return [
                    'upload' => '#',
                    'remove' => '#',
                    'dashboard' => '#'
                ];
        }
    }

    /**
     * Get dashboard route based on user type
     */
    public function getDashboardRoute($userType)
    {
        switch ($userType) {
            case 'student':
                return route('estudiante.dashboard');
            case 'teacher':
                return route('profesor.dashboard');
            case 'coordinator':
                return route('coordinador.dashboard');
            case 'admin':
                return route('admin.dashboard');
            default:
                return route('welcome');
        }
    }

    /**
     * Get profile photo URL for a user
     */
    public static function getProfilePhotoUrl($user)
    {
        if ($user && $user->profile_photo) {
            return asset('storage/profile-photos/' . $user->profile_photo);
        }
        
        return asset('images/default-avatar.svg');
    }
}
