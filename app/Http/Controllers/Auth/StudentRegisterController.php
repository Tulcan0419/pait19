<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $careers = [
            'mechanical' => 'Mecánica',
            'software' => 'Desarrollo de Software',
            'education' => 'Educación Básica',
        ];
        
        $semesters = [
            1 => 'Primer Semestre',
            2 => 'Segundo Semestre',
            3 => 'Tercer Semestre',
            4 => 'Cuarto Semestre',
        ];
        
        return view('auth.student.register', compact('careers', 'semesters'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $student = $this->create($request->all());
        Auth::guard('student')->login($student);

        // Redirección directa al dashboard de carrera
        return redirect()->route('estudiante.dashboard.career', ['career' => $student->career]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'student_code' => ['required', 'string', 'unique:students'],
            'career' => ['required', 'string', 'in:mechanical,software,education'],
            'semester' => ['required', 'integer', 'min:1', 'max:4'],
        ]);
    }

    protected function create(array $data)
    {
        return Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'student_code' => $data['student_code'],
            'career' => $data['career'],
            'semester' => $data['semester'],
        ]);
    }
}
