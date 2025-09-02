<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Document;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherCareerDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del profesor con estadísticas de todas las carreras
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Obtener estadísticas generales
        $totalStudents = $this->getTotalStudents($teacher);
        $totalSubjects = $this->getTotalSubjects($teacher);
        $totalDocuments = $this->getTotalDocuments($teacher);
        $pendingReviews = $this->getPendingReviews($teacher);
        
        // Obtener estadísticas por carrera
        $softwareStats = $this->getCareerStats($teacher, 'software');
        $mechanicalStats = $this->getCareerStats($teacher, 'mechanical');
        $educationStats = $this->getCareerStats($teacher, 'education');
        
        // Obtener notificaciones recientes
        $recentNotifications = $this->getRecentNotifications($teacher);
        
        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalSubjects', 
            'totalDocuments',
            'pendingReviews',
            'softwareStats',
            'mechanicalStats',
            'educationStats',
            'recentNotifications'
        ));
    }

    /**
     * Muestra el dashboard específico de una carrera
     */
    public function showCareerDashboard($career)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Validar que la carrera sea válida
        $validCareers = ['software', 'mechanical', 'education'];
        if (!in_array($career, $validCareers)) {
            abort(404);
        }
        
        // Obtener estadísticas de la carrera
        $careerStats = $this->getDetailedCareerStats($teacher, $career);
        
        // Obtener materias organizadas por semestre
        $subjectsBySemester = $this->getSubjectsBySemester($teacher, $career);
        
        // Obtener proyectos destacados según la carrera
        $featuredProjects = $this->getFeaturedProjects($teacher, $career);
        
        // Obtener estudiantes destacados
        $topStudents = $this->getTopStudents($teacher, $career);
        
        // Obtener datos específicos según la carrera
        $careerSpecificData = $this->getCareerSpecificData($teacher, $career);
        
        // Combinar todos los datos
        $data = array_merge($careerStats, [
            'subjectsBySemester' => $subjectsBySemester,
            'featuredProjects' => $featuredProjects,
            'topStudents' => $topStudents,
            'currentSemester' => 1, // Por defecto mostrar primer semestre
        ], $careerSpecificData);
        
        return view("teacher.careers.{$career}", $data);
    }

    /**
     * Obtiene el total de estudiantes asignados al profesor
     */
    private function getTotalStudents($teacher)
    {
        return Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->count();
    }

    /**
     * Obtiene el total de materias asignadas al profesor
     */
    private function getTotalSubjects($teacher)
    {
        return $teacher->activeSubjectAssignments()->count();
    }

    /**
     * Obtiene el total de documentos pendientes de revisión
     */
    private function getTotalDocuments($teacher)
    {
        return Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->where('status', 'pending')->count();
    }

    /**
     * Obtiene el total de revisiones pendientes
     */
    private function getPendingReviews($teacher)
    {
        return Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->whereIn('status', ['pending', 'in_review'])->count();
    }

    /**
     * Obtiene estadísticas básicas de una carrera específica
     */
    private function getCareerStats($teacher, $career)
    {
        $students = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->where('career', $career);
        
        $subjects = Subject::where('career', $career)->whereHas('subjectAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        });
        
        $documents = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->whereHas('student', function($query) use ($career) {
            $query->where('career', $career);
        });
        
        return [
            'students' => $students->count(),
            'subjects' => $subjects->count(),
            'documents' => $documents->count(),
        ];
    }

    /**
     * Obtiene estadísticas detalladas de una carrera específica
     */
    private function getDetailedCareerStats($teacher, $career)
    {
        // Obtener estudiantes de la carrera
        $students = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->where('career', $career);
        
        $totalStudents = $students->count();
        
        // Obtener materias de la carrera
        $subjects = Subject::where('career', $career)->whereHas('subjectAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        });
        
        $totalSubjects = $subjects->count();
        
        // Obtener calificaciones y calcular promedio
        $grades = Grade::whereHas('subject', function($query) use ($career) {
            $query->where('career', $career);
        })->whereHas('subject.subjectAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        });
        
        $totalGrades = $grades->count();
        $averageGrade = $totalGrades > 0 ? round($grades->avg('grade'), 2) : 'N/A';
        
        // Obtener documentos
        $documents = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->whereHas('student', function($query) use ($career) {
            $query->where('career', $career);
        });
        
        $totalDocuments = $documents->count();
        $pendingDocuments = $documents->where('status', 'pending')->count();
        
        // Calcular tasa de graduación (simulado)
        $graduatedStudents = $this->getGraduatedStudents($teacher, $career);
        $graduationRate = $totalStudents > 0 ? round(($graduatedStudents / $totalStudents) * 100, 1) : 'N/A';
        
        return [
            'totalStudents' => $totalStudents,
            'totalSubjects' => $totalSubjects,
            'totalGrades' => $totalGrades,
            'averageGrade' => $averageGrade,
            'totalDocuments' => $totalDocuments,
            'pendingDocuments' => $pendingDocuments,
            'graduatedStudents' => $graduatedStudents,
            'graduationRate' => $graduationRate,
        ];
    }

    /**
     * Obtiene materias organizadas por semestre para una carrera
     */
    private function getSubjectsBySemester($teacher, $career)
    {
        $subjects = Subject::where('career', $career)
            ->whereHas('subjectAssignments', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)->where('status', 'active');
            })
            ->with(['grades', 'subjectAssignments'])
            ->get()
            ->groupBy('semester');
        
        // Agregar estadísticas a cada materia
        foreach ($subjects as $semester => $semesterSubjects) {
            foreach ($semesterSubjects as $subject) {
                $subject->students_count = $subject->subjectAssignments->where('teacher_id', $teacher->id)->count();
                $subject->average_grade = $subject->grades->count() > 0 ? round($subject->grades->avg('grade'), 2) : 'N/A';
            }
        }
        
        return $subjects;
    }

    /**
     * Obtiene proyectos destacados según la carrera
     */
    private function getFeaturedProjects($teacher, $career)
    {
        // Esta función simula la obtención de proyectos
        // En una implementación real, se conectaría con el modelo de proyectos
        return collect([]);
    }

    /**
     * Obtiene estudiantes destacados de una carrera
     */
    private function getTopStudents($teacher, $career)
    {
        return Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })
        ->where('career', $career)
        ->with(['grades', 'tutorAssignments'])
        ->get()
        ->map(function ($student) {
            $student->average_grade = $student->grades->count() > 0 ? round($student->grades->avg('grade'), 2) : 'N/A';
            $student->subjects_count = $student->tutorAssignments->where('status', 'active')->count();
            return $student;
        })
        ->sortByDesc('average_grade')
        ->take(5);
    }

    /**
     * Obtiene datos específicos según la carrera
     */
    private function getCareerSpecificData($teacher, $career)
    {
        switch ($career) {
            case 'software':
                return $this->getSoftwareSpecificData($teacher);
            case 'mechanical':
                return $this->getMechanicalSpecificData($teacher);
            case 'education':
                return $this->getEducationSpecificData($teacher);
            default:
                return [];
        }
    }

    /**
     * Obtiene datos específicos para la carrera de Software
     */
    private function getSoftwareSpecificData($teacher)
    {
        // Simular datos específicos de software
        return [
            'totalProjects' => 0,
            'activeProjects' => 0,
            'completedProjects' => 0,
        ];
    }

    /**
     * Obtiene datos específicos para la carrera de Mecánica
     */
    private function getMechanicalSpecificData($teacher)
    {
        // Simular datos específicos de mecánica
        return [
            'totalPractices' => 0,
            'pendingPractices' => 0,
            'workshopPractices' => collect([]),
            'vehicleProjects' => collect([]),
            'activeProjects' => 0,
            'completedProjects' => 0,
        ];
    }

    /**
     * Obtiene datos específicos para la carrera de Educación
     */
    private function getEducationSpecificData($teacher)
    {
        // Simular datos específicos de educación
        return [
            'totalPracticums' => 0,
            'pendingPracticums' => 0,
            'practicums' => collect([]),
            'educationalProjects' => collect([]),
            'activeProjects' => 0,
            'completedProjects' => 0,
        ];
    }

    /**
     * Obtiene el número de estudiantes graduados (simulado)
     */
    private function getGraduatedStudents($teacher, $career)
    {
        // En una implementación real, se verificaría el estado de graduación
        // Por ahora, simulamos un 15% de graduación
        $totalStudents = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->where('career', $career)->count();
        
        return round($totalStudents * 0.15);
    }

    /**
     * Obtiene notificaciones recientes del profesor
     */
    private function getRecentNotifications($teacher)
    {
        // En una implementación real, se obtendrían las notificaciones reales
        // Por ahora, retornamos una colección vacía
        return collect([]);
    }
}
