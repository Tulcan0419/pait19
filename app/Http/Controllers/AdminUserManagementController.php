<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Coordinator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserManagementController extends Controller
{
    // Mostrar todos los usuarios
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $admins = $type === 'admin' || $type === 'all' ? Admin::all() : collect();
        $students = $type === 'student' || $type === 'all' ? Student::all() : collect();
        $teachers = $type === 'teacher' || $type === 'all' ? Teacher::all() : collect();
        $coordinators = $type === 'coordinator' || $type === 'all' ? Coordinator::all() : collect();
        return view('auth.admin.users.index', compact('admins', 'students', 'teachers', 'coordinators', 'type'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('auth.admin.users.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $type = $request->input('type');
        $data = $request->except(['_token', 'type', 'subjects']);
        $data['password'] = Hash::make($request->input('password'));
        switch ($type) {
            case 'admin':
                Admin::create($data);
                break;
            case 'student':
                Student::create($data);
                break;
            case 'teacher':
                $teacher = Teacher::create($data);
                if ($request->has('subjects')) {
                    $teacher->subjects()->sync($request->input('subjects'));
                }
                break;
            case 'coordinator':
                Coordinator::create($data);
                break;
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit($type, $id)
    {
        $user = null;
        switch ($type) {
            case 'admin':
                $user = Admin::findOrFail($id);
                break;
            case 'student':
                $user = Student::findOrFail($id);
                break;
            case 'teacher':
                $user = Teacher::findOrFail($id);
                break;
            case 'coordinator':
                $user = Coordinator::findOrFail($id);
                break;
        }
        return view('auth.admin.users.edit', compact('user', 'type'));
    }

    // Actualizar usuario
    public function update(Request $request, $type, $id)
    {
        $data = $request->except(['_token', '_method', 'type', 'password', 'subjects']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        switch ($type) {
            case 'admin':
                Admin::findOrFail($id)->update($data);
                break;
            case 'student':
                $student = Student::findOrFail($id);
                $student->update($data);
                if ($request->has('subjects')) {
                    $student->subjects()->sync($request->input('subjects'));
                } else {
                    $student->subjects()->detach();
                }
                break;
            case 'teacher':
                $teacher = Teacher::findOrFail($id);
                $teacher->update($data);
                if ($request->has('subjects')) {
                    $teacher->subjects()->sync($request->input('subjects'));
                } else {
                    $teacher->subjects()->detach();
                }
                break;
            case 'coordinator':
                Coordinator::findOrFail($id)->update($data);
                break;
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($type, $id)
    {
        switch ($type) {
            case 'admin':
                Admin::destroy($id);
                break;
            case 'student':
                Student::destroy($id);
                break;
            case 'teacher':
                Teacher::destroy($id);
                break;
            case 'coordinator':
                Coordinator::destroy($id);
                break;
        }
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
} 