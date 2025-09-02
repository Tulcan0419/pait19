<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentStatisticsController extends Controller
{
    public function index()
    {
        // Estadísticas por carrera y semestre
        $statistics = [
            'mechanical' => [
                'total' => Student::where('career', 'mechanical')->count(),
                'by_semester' => Student::where('career', 'mechanical')
                    ->selectRaw('semester, count(*) as count')
                    ->groupBy('semester')
                    ->orderBy('semester')
                    ->get()
            ],
            'software' => [
                'total' => Student::where('career', 'software')->count(),
                'by_semester' => Student::where('career', 'software')
                    ->selectRaw('semester, count(*) as count')
                    ->groupBy('semester')
                    ->orderBy('semester')
                    ->get()
            ],
            'education' => [
                'total' => Student::where('career', 'education')->count(),
                'by_semester' => Student::where('career', 'education')
                    ->selectRaw('semester, count(*) as count')
                    ->groupBy('semester')
                    ->orderBy('semester')
                    ->get()
            ]
        ];

        // Estadísticas generales
        $generalStats = [
            'total_students' => Student::count(),
            'average_semester' => round(Student::avg('semester'), 1),
            'semester_distribution' => Student::selectRaw('semester, count(*) as count')
                ->groupBy('semester')
                ->orderBy('semester')
                ->get()
        ];

        return view('admin.statistics.index', compact('statistics', 'generalStats'));
    }

    public function byCareer($career)
    {
        $students = Student::where('career', $career)
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        $careerNames = [
            'mechanical' => 'Mecánica',
            'software' => 'Desarrollo de Software',
            'education' => 'Educación Básica'
        ];

        $careerName = $careerNames[$career] ?? ucfirst($career);

        return view('admin.statistics.by_career', compact('students', 'careerName', 'career'));
    }

    public function bySemester($semester)
    {
        $students = Student::where('semester', $semester)
            ->orderBy('career')
            ->orderBy('name')
            ->get();

        $semesterNames = [
            1 => 'Primer Semestre',
            2 => 'Segundo Semestre',
            3 => 'Tercer Semestre',
            4 => 'Cuarto Semestre'
        ];

        $semesterName = $semesterNames[$semester] ?? "Semestre {$semester}";

        return view('admin.statistics.by_semester', compact('students', 'semesterName', 'semester'));
    }
}
