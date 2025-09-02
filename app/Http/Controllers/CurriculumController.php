<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculumController extends Controller
{
    /**
     * Mostrar la malla curricular completa
     */
    public function index(Request $request)
    {
        $career = $request->get('career', 'software');
        $student = null;
        $studentProgress = null;

        // Si hay un estudiante autenticado, obtener su progreso
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $studentProgress = $this->getStudentProgress($student);
        }

        // Obtener materias organizadas por semestre
        $subjects = Subject::byCareer($career)
            ->active()
            ->orderBy('semester')
            ->orderBy('curricular_unit')
            ->orderBy('name')
            ->get()
            ->groupBy('semester');

        // Calcular estadísticas
        $stats = $this->calculateCurriculumStats($subjects);

        return view('curriculum.index', compact('subjects', 'career', 'student', 'studentProgress', 'stats'));
    }

    /**
     * Mostrar el progreso personal del estudiante
     */
    public function myProgress()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('estudiante.login');
        }

        $progress = $this->getStudentProgress($student);
        $subjects = Subject::byCareer($student->career)
            ->active()
            ->orderBy('semester')
            ->orderBy('curricular_unit')
            ->orderBy('name')
            ->get()
            ->groupBy('semester');

        // Obtener materias del semestre actual
        $currentSemesterSubjects = Subject::byCareer($student->career)
            ->bySemester($student->semester)
            ->active()
            ->get();

        return view('curriculum.my-progress', compact('student', 'progress', 'subjects', 'currentSemesterSubjects'));
    }

    /**
     * Obtener el progreso de un estudiante
     */
    private function getStudentProgress($student)
    {
        $progress = [];

        // Obtener todas las materias de la carrera del estudiante
        $subjects = Subject::byCareer($student->career)->active()->get();

        foreach ($subjects as $subject) {
            $grades = Grade::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->get();

            $averageGrade = $grades->avg('grade');
            $isEnrolled = $student->subjects->contains($subject->id);
            $isCompleted = $averageGrade >= 7.0; // Nota mínima de aprobación

            $progress[$subject->id] = [
                'subject' => $subject,
                'is_enrolled' => $isEnrolled,
                'is_completed' => $isCompleted,
                'average_grade' => $averageGrade,
                'grades_count' => $grades->count(),
                'last_grade_date' => $grades->max('evaluation_date'),
            ];
        }

        return $progress;
    }

    /**
     * Calcular estadísticas de la malla curricular
     */
    private function calculateCurriculumStats($subjects)
    {
        $stats = [
            'total_subjects' => 0,
            'total_credits' => 0,
            'by_unit' => [
                'basica' => ['count' => 0, 'credits' => 0],
                'profesional' => ['count' => 0, 'credits' => 0],
                'integracion' => ['count' => 0, 'credits' => 0],
            ],
            'by_semester' => []
        ];

        foreach ($subjects as $semester => $semesterSubjects) {
            $stats['by_semester'][$semester] = [
                'count' => $semesterSubjects->count(),
                'credits' => $semesterSubjects->sum('credits'),
                'units' => [
                    'basica' => $semesterSubjects->where('curricular_unit', 'basica')->count(),
                    'profesional' => $semesterSubjects->where('curricular_unit', 'profesional')->count(),
                    'integracion' => $semesterSubjects->where('curricular_unit', 'integracion')->count(),
                ]
            ];

            $stats['total_subjects'] += $semesterSubjects->count();
            $stats['total_credits'] += $semesterSubjects->sum('credits');

            foreach ($semesterSubjects as $subject) {
                $stats['by_unit'][$subject->curricular_unit]['count']++;
                $stats['by_unit'][$subject->curricular_unit]['credits'] += $subject->credits;
            }
        }

        return $stats;
    }

    /**
     * Mostrar detalles de una materia específica
     */
    public function showSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $student = Auth::guard('student')->user();
        $grades = collect();

        if ($student) {
            $grades = Grade::where('student_id', $student->id)
                ->where('subject_id', $id)
                ->orderBy('evaluation_date', 'desc')
                ->get();
        }

        return view('curriculum.subject-details', compact('subject', 'student', 'grades'));
    }

    /**
     * API para obtener materias por año académico
     */
    public function getSubjectsByYear(Request $request)
    {
        $career = $request->get('career', 'software');
        $year = $request->get('year');

        $query = Subject::byCareer($career)->active();

        if ($year) {
            $query->byAcademicYear($year);
        }

        $subjects = $query->orderBy('curricular_unit')
            ->orderBy('name')
            ->get()
            ->groupBy('curricular_unit');

        return response()->json($subjects);
    }
} 