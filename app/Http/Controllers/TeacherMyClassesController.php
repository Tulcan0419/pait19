<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\SubjectTeacher;

class TeacherMyClassesController extends Controller
{
    /**
     * Mostrar las clases asignadas al profesor
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Obtener materias del período actual
        $currentSubjects = $teacher->getCurrentSubjects();
        
        // Calcular total de estudiantes
        $totalStudents = $currentSubjects->sum(function ($subject) {
            return $subject->students()->count();
        });
        
        // Obtener período actual
        $currentPeriod = $teacher->getCurrentAcademicPeriod();
        
        // Obtener materias de períodos anteriores (últimos 2 años)
        $previousPeriods = $this->getPreviousPeriods($teacher);
        
        return view('auth.teacher.my_classes', compact(
            'teacher',
            'currentSubjects',
            'totalStudents',
            'currentPeriod',
            'previousPeriods'
        ));
    }

    /**
     * Obtener materias de períodos anteriores
     */
    private function getPreviousPeriods($teacher)
    {
        $currentYear = date('Y');
        $previousPeriods = [];
        
        // Obtener asignaciones de los últimos 2 años (excluyendo el actual)
        for ($i = 1; $i <= 2; $i++) {
            $year = $currentYear - $i;
            $period = ($year - 1) . '-' . $year;
            
            $subjects = $teacher->getSubjectsByPeriod($period);
            
            if ($subjects->isNotEmpty()) {
                $previousPeriods[$period] = $subjects;
            }
        }
        
        return collect($previousPeriods);
    }

    /**
     * Mostrar detalles de una materia específica
     */
    public function show($subjectId)
    {
        $teacher = Auth::guard('teacher')->user();
        $currentPeriod = $teacher->getCurrentAcademicPeriod();
        
        // Verificar que el profesor tenga asignada esta materia en el período actual
        $assignment = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId)
            ->where('academic_period', $currentPeriod)
            ->where('status', 'active')
            ->with('subject')
            ->first();
            
        if (!$assignment) {
            return redirect()->route('profesor.my-classes')
                ->with('error', 'Materia no encontrada o no asignada.');
        }
        
        $subject = $assignment->subject;
        $students = $subject->students()->get();
        
        return view('auth.teacher.class_details', compact('subject', 'students', 'assignment'));
    }

    /**
     * Mostrar estadísticas de una materia
     */
    public function statistics($subjectId)
    {
        $teacher = Auth::guard('teacher')->user();
        $currentPeriod = $teacher->getCurrentAcademicPeriod();
        
        // Verificar que el profesor tenga asignada esta materia
        $assignment = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId)
            ->where('academic_period', $currentPeriod)
            ->where('status', 'active')
            ->with('subject')
            ->first();
            
        if (!$assignment) {
            return redirect()->route('profesor.my-classes')
                ->with('error', 'Materia no encontrada o no asignada.');
        }
        
        $subject = $assignment->subject;
        $students = $subject->students()->with('grades')->get();
        
        // Calcular estadísticas
        $stats = $this->calculateSubjectStats($subject, $students);
        
        return view('auth.teacher.class_statistics', compact('subject', 'students', 'stats'));
    }

    /**
     * Calcular estadísticas de la materia
     */
    private function calculateSubjectStats($subject, $students)
    {
        $totalStudents = $students->count();
        $enrolledStudents = $students->where('pivot.status', 'enrolled')->count();
        $averageGrade = $subject->getAverageGrade();
        
        // Calcular distribución de calificaciones
        $gradeDistribution = [
            'excellent' => $students->filter(function ($student) {
                $grade = $student->grades->where('subject_id', $student->pivot->subject_id)->first();
                return $grade && $grade->grade >= 9.0;
            })->count(),
            'good' => $students->filter(function ($student) {
                $grade = $student->grades->where('subject_id', $student->pivot->subject_id)->first();
                return $grade && $grade->grade >= 7.0 && $grade->grade < 9.0;
            })->count(),
            'average' => $students->filter(function ($student) {
                $grade = $student->grades->where('subject_id', $student->pivot->subject_id)->first();
                return $grade && $grade->grade >= 5.0 && $grade->grade < 7.0;
            })->count(),
            'failing' => $students->filter(function ($student) {
                $grade = $student->grades->where('subject_id', $student->pivot->subject_id)->first();
                return $grade && $grade->grade < 5.0;
            })->count(),
        ];
        
        return [
            'total_students' => $totalStudents,
            'enrolled_students' => $enrolledStudents,
            'average_grade' => $averageGrade,
            'grade_distribution' => $gradeDistribution,
            'passing_rate' => $totalStudents > 0 ? 
                (($gradeDistribution['excellent'] + $gradeDistribution['good'] + $gradeDistribution['average']) / $totalStudents) * 100 : 0
        ];
    }
} 