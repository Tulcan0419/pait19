<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentTutorAssignment;

class CoordinatorStudentTutorAssignmentController extends Controller
{
    /**
     * Mapeo de carreras en español
     */
    private $careerTitles = [
        'mechanical' => 'Mecánica Automotriz',
        'software' => 'Desarrollo de Software',
        'education' => 'Educación Básica',
    ];

    /**
     * Muestra la vista para asignar tutores a estudiantes.
     */
    public function index(Request $request)
    {
        $students = Student::with('activeTutorAssignment.teacher')->get();
        $teachers = Teacher::all();
        
        $selectedStudent = null;

        if ($request->has('student_id') && $request->student_id) {
            $selectedStudent = Student::with('activeTutorAssignment.teacher')->find($request->student_id);
        }

        return view('auth.coordinator.student_tutor_assignment.index', compact('students', 'teachers', 'selectedStudent'));
    }

    /**
     * Asigna un tutor a un estudiante específico.
     */
    public function assignTutor(Request $request, Student $student)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'comments' => 'nullable|string|max:500',
        ]);

        try {
            // Desactivar cualquier asignación previa del estudiante
            StudentTutorAssignment::where('student_id', $student->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);

            // Verificar si ya existe una asignación (activa o inactiva) entre este estudiante y profesor
            $existingAssignment = StudentTutorAssignment::where('student_id', $student->id)
                ->where('teacher_id', $request->teacher_id)
                ->first();

            if ($existingAssignment) {
                // Si existe, actualizar la asignación existente a activa
                $existingAssignment->update([
                    'status' => 'active',
                    'comments' => $request->comments,
                ]);
            } else {
                // Si no existe, crear una nueva asignación
                StudentTutorAssignment::create([
                    'student_id' => $student->id,
                    'teacher_id' => $request->teacher_id,
                    'status' => 'active',
                    'comments' => $request->comments,
                ]);
            }

            return redirect()->back()->with('success', 'Tutor asignado exitosamente al estudiante.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al asignar el tutor: ' . $e->getMessage());
        }
    }

    /**
     * Remueve el tutor asignado a un estudiante.
     */
    public function removeTutor(Student $student)
    {
        StudentTutorAssignment::where('student_id', $student->id)
            ->where('status', 'active')
            ->update(['status' => 'inactive']);

        return redirect()->back()->with('success', 'Tutor removido exitosamente del estudiante.');
    }

    /**
     * Muestra un reporte de asignaciones de tutores por estudiante.
     */
    public function report()
    {
        $teachers = Teacher::withCount('activeStudentTutorAssignments')->get();
        $studentsWithoutTutor = Student::whereDoesntHave('tutorAssignments', function($query) {
            $query->where('status', 'active');
        })->get();
        $studentsWithTutor = Student::whereHas('tutorAssignments', function($query) {
            $query->where('status', 'active');
        })->with('activeTutorAssignment.teacher')->get();

        return view('auth.coordinator.student_tutor_assignment.report', compact('teachers', 'studentsWithoutTutor', 'studentsWithTutor'));
    }
} 