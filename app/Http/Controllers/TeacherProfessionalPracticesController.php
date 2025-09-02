<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student; // Importa el modelo Student
use App\Models\Document; // Importa el modelo Document
use App\Models\StudentTutorAssignment; // Importa el modelo StudentTutorAssignment
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DocumentStatusNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherProfessionalPracticesController extends Controller
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
     * Muestra las prácticas profesionales organizadas por carreras.
     * Solo muestra estudiantes asignados al profesor autenticado como tutor.
     * Opcionalmente, filtra por una carrera específica y/o por nombre/código de estudiante.
     */
    public function index(Request $request)
    {
        try {
            $teacher = Auth::guard('teacher')->user();
            
            if (!$teacher) {
                return redirect()->route('profesor.login');
            }
            
            // Obtener carreras que tienen estudiantes asignados al profesor como tutor
            $careers = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)->where('status', 'active');
            })->distinct()->pluck('career')->filter()->sort()->values();
            
            // Obtener parámetros de filtrado
            $selectedCareer = $request->get('career');
            $searchTerm = $request->get('search');
            
            // Verificar si el profesor tiene estudiantes asignados
            $hasAssignedStudents = $careers->count() > 0;
            
            if ($selectedCareer) {
                // Obtener estudiantes de la carrera seleccionada que están asignados al profesor como tutor
                $studentsQuery = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id)->where('status', 'active');
                })->with(['documents', 'activeTutorAssignment.teacher'])->where('career', $selectedCareer);
                
                // Aplicar filtro de búsqueda si se proporciona
                if ($searchTerm) {
                    $searchTerm = trim($searchTerm);
                    $studentsQuery->where(function($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('student_code', 'LIKE', "%{$searchTerm}%")
                              ->orWhereRaw("LOWER(name) LIKE LOWER(?)", ["%{$searchTerm}%"]);
                    });
                }
                
                $students = $studentsQuery->orderBy('name')->get();
                    
                // Agrupar documentos por estudiante
                $studentsWithDocuments = $students->map(function ($student) {
                    return [
                        'student' => $student,
                        'documents' => $student->documents()->latest()->get()
                    ];
                });
            } else {
                // Si no se selecciona carrera, mostrar todas las carreras con resumen
                $studentsWithDocuments = collect();
                
                foreach ($careers as $career) {
                    $studentsInCareerQuery = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id)->where('status', 'active');
                    })->with(['documents', 'activeTutorAssignment.teacher'])->where('career', $career);
                    
                    // Aplicar filtro de búsqueda si se proporciona
                    if ($searchTerm) {
                        $searchTerm = trim($searchTerm);
                        $studentsInCareerQuery->where(function($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('student_code', 'LIKE', "%{$searchTerm}%")
                                  ->orWhereRaw("LOWER(name) LIKE LOWER(?)", ["%{$searchTerm}%"]);
                        });
                    }
                    
                    $studentsInCareer = $studentsInCareerQuery->orderBy('name')->get();
                        
                    $totalDocuments = $studentsInCareer->sum(function ($student) {
                        return $student->documents->count();
                    });
                    
                    // Solo agregar la carrera si tiene estudiantes que coinciden con la búsqueda
                    if ($studentsInCareer->count() > 0) {
                        $studentsWithDocuments->push([
                            'career' => $career,
                            'career_title' => $this->careerTitles[$career] ?? ucfirst($career),
                            'students' => $studentsInCareer,
                            'total_students' => $studentsInCareer->count(),
                            'total_documents' => $totalDocuments
                        ]);
                    }
                }
            }

            // Preparar las carreras para el selector con nombres en español
            $careersForSelect = $careers->mapWithKeys(function ($career) {
                return [$career => $this->careerTitles[$career] ?? ucfirst($career)];
            });

            return view('auth.teacher.professional_practices.index', compact('careersForSelect', 'selectedCareer', 'searchTerm', 'studentsWithDocuments', 'hasAssignedStudents'));
            
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en TeacherProfessionalPracticesController: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Redirigir con mensaje de error
            return redirect()->route('profesor.dashboard')
                ->with('error', 'Ha ocurrido un error al cargar las prácticas preprofesionales. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Muestra la vista detallada de revisión de un documento específico.
     */
    public function documentReview(Document $document)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Verificar que el profesor sea tutor del estudiante que posee el documento
        $isTutor = StudentTutorAssignment::where('teacher_id', $teacher->id)
            ->where('student_id', $document->student_id)
            ->where('status', 'active')
            ->exists();
        
        if (!$isTutor) {
            abort(403, 'No tienes permisos para revisar este documento.');
        }

        $student = $document->student;
        $careerTitles = $this->careerTitles;

        return view('auth.teacher.professional_practices.document_review', compact('document', 'student', 'careerTitles'));
    }

    /**
     * Muestra las estadísticas de documentos revisados por el profesor.
     */
    public function statistics(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Obtener parámetros de filtrado
        $selectedCareer = $request->get('career');
        $selectedPeriod = $request->get('period', 'month');
        
        // Obtener carreras disponibles
        $careers = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->distinct()->pluck('career')->filter()->sort()->values();
        
        $careersForSelect = $careers->mapWithKeys(function ($career) {
            return [$career => $this->careerTitles[$career] ?? ucfirst($career)];
        });

        // Construir consulta base
        $documentsQuery = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->with(['student']);

        // Aplicar filtros
        if ($selectedCareer) {
            $documentsQuery->whereHas('student', function($query) use ($selectedCareer) {
                $query->where('career', $selectedCareer);
            });
        }

        // Aplicar filtro de período
        $this->applyPeriodFilter($documentsQuery, $selectedPeriod);

        // Obtener estadísticas generales
        $totalDocuments = $documentsQuery->count();
        $pendingDocuments = (clone $documentsQuery)->whereNull('teacher_status')->orWhere('teacher_status', 'pending')->count();
        $approvedDocuments = (clone $documentsQuery)->where('teacher_status', 'approved')->count();
        $rejectedDocuments = (clone $documentsQuery)->where('teacher_status', 'rejected')->count();

        // Obtener progreso mensual
        $monthlyProgress = $this->getMonthlyProgress($teacher, $selectedCareer, $selectedPeriod);

        // Obtener estadísticas por carrera si se selecciona una
        $careerStats = null;
        if ($selectedCareer) {
            $careerStats = $this->getCareerStats($teacher, $selectedCareer, $selectedPeriod);
        }

        // Obtener documentos recientes
        $recentDocuments = (clone $documentsQuery)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('auth.teacher.professional_practices.statistics', compact(
            'careersForSelect',
            'selectedCareer',
            'selectedPeriod',
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'rejectedDocuments',
            'monthlyProgress',
            'careerStats',
            'recentDocuments'
        ));
    }

    /**
     * Muestra la gestión de comentarios y retroalimentación.
     */
    public function comments(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Obtener parámetros de filtrado
        $selectedCareer = $request->get('career');
        $selectedStatus = $request->get('status');
        $hasComments = $request->get('has_comments');
        
        // Obtener carreras disponibles
        $careers = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->distinct()->pluck('career')->filter()->sort()->values();
        
        $careersForSelect = $careers->mapWithKeys(function ($career) {
            return [$career => $this->careerTitles[$career] ?? ucfirst($career)];
        });

        // Construir consulta base
        $documentsQuery = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->with(['student']);

        // Aplicar filtros
        if ($selectedCareer) {
            $documentsQuery->whereHas('student', function($query) use ($selectedCareer) {
                $query->where('career', $selectedCareer);
            });
        }

        if ($selectedStatus) {
            $documentsQuery->where('teacher_status', $selectedStatus);
        }

        if ($hasComments === 'yes') {
            $documentsQuery->whereNotNull('teacher_comments')->where('teacher_comments', '!=', '');
        } elseif ($hasComments === 'no') {
            $documentsQuery->whereNull('teacher_comments')->orWhere('teacher_comments', '');
        }

        // Obtener estadísticas de comentarios
        $totalComments = (clone $documentsQuery)->whereNotNull('teacher_comments')->where('teacher_comments', '!=', '')->count();
        $pendingReviews = (clone $documentsQuery)->whereNull('teacher_status')->orWhere('teacher_status', 'pending')->count();
        $completedReviews = (clone $documentsQuery)->whereNotNull('teacher_status')->where('teacher_status', '!=', 'pending')->count();

        // Obtener documentos con comentarios
        $documentsWithComments = $documentsQuery
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('auth.teacher.professional_practices.comments', compact(
            'careersForSelect',
            'selectedCareer',
            'selectedStatus',
            'hasComments',
            'totalComments',
            'pendingReviews',
            'completedReviews',
            'documentsWithComments'
        ));
    }

    /**
     * Actualiza el estado de un documento.
     */
    public function updateStatus(Request $request, Document $document)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Verificar que el profesor sea tutor del estudiante que posee el documento
        $isTutor = StudentTutorAssignment::where('teacher_id', $teacher->id)
            ->where('student_id', $document->student_id)
            ->where('status', 'active')
            ->exists();
        
        if (!$isTutor) {
            return redirect()->back()->with('error', 'No tienes permisos para modificar este documento.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string|max:1000',
        ]);

        // Validar que se proporcionen comentarios si se rechaza el documento
        if ($request->status === 'rejected' && empty($request->comments)) {
            return redirect()->back()->withErrors(['comments' => 'Los comentarios son obligatorios cuando se rechaza un documento.']);
        }

        // Guardar el estado anterior para comparar
        $previousStatus = $document->teacher_status;

        // El profesor puede cambiar el estado en cualquier momento
        $document->teacher_status = $request->status;
        $document->teacher_comments = $request->comments;
        $document->save();

        // Enviar notificación al estudiante si el estado cambió
        if ($previousStatus !== $request->status) {
            $student = $document->student;
            $student->notify(new DocumentStatusNotification(
                $document, 
                $request->status, 
                'teacher', 
                $request->comments
            ));
        }

        return redirect()->back()->with('success', 'Estado del documento actualizado exitosamente.');
    }

    /**
     * Descarga un documento.
     */
    public function downloadDocument(Document $document)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Verificar que el profesor sea tutor del estudiante que posee el documento
        $isTutor = StudentTutorAssignment::where('teacher_id', $teacher->id)
            ->where('student_id', $document->student_id)
            ->where('status', 'active')
            ->exists();
        
        if (!$isTutor) {
            abort(403, 'No tienes permisos para descargar este documento.');
        }

        return Storage::download($document->file_path, $document->file_name);
    }

    /**
     * Exporta las estadísticas en el formato especificado.
     */
    public function exportStatistics(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $format = $request->get('format', 'pdf');
        $career = $request->get('career');
        $period = $request->get('period', 'month');

        // Obtener datos para exportar
        $data = $this->getStatisticsData($teacher, $career, $period);

        if ($format === 'pdf') {
            return $this->exportToPdf($data, $career, $period);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($data, $career, $period);
        }

        return redirect()->back()->with('error', 'Formato de exportación no válido.');
    }

    /**
     * Exporta los comentarios en el formato especificado.
     */
    public function exportComments(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $format = $request->get('format', 'pdf');
        $career = $request->get('career');
        $status = $request->get('status');

        // Obtener comentarios para exportar
        $data = $this->getCommentsData($teacher, $career, $status);

        if ($format === 'pdf') {
            return $this->exportCommentsToPdf($data, $career, $status);
        } elseif ($format === 'excel') {
            return $this->exportCommentsToExcel($data, $career, $status);
        }

        return redirect()->back()->with('error', 'Formato de exportación no válido.');
    }

    /**
     * Aplica filtro de período a la consulta.
     */
    private function applyPeriodFilter($query, $period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'month':
                $query->where('created_at', '>=', $now->subMonth());
                break;
            case 'quarter':
                $query->where('created_at', '>=', $now->subQuarter());
                break;
            case 'semester':
                $query->where('created_at', '>=', $now->subMonths(6));
                break;
            case 'year':
                $query->where('created_at', '>=', $now->subYear());
                break;
            // 'all' no aplica filtro de tiempo
        }
    }

    /**
     * Obtiene el progreso mensual de documentos.
     */
    private function getMonthlyProgress($teacher, $career = null, $period = 'month')
    {
        $query = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        });

        if ($career) {
            $query->whereHas('student', function($q) use ($career) {
                $q->where('career', $career);
            });
        }

        $this->applyPeriodFilter($query, $period);

        $monthlyData = $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $monthlyData->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        })->toArray();

        $data = $monthlyData->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Obtiene estadísticas específicas por carrera.
     */
    private function getCareerStats($teacher, $career, $period)
    {
        $query = Document::whereHas('student.tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->whereHas('student', function($query) use ($career) {
            $query->where('career', $career);
        });

        $this->applyPeriodFilter($query, $period);

        $totalStudents = Student::whereHas('tutorAssignments', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id)->where('status', 'active');
        })->where('career', $career)->count();

        $totalDocuments = $query->count();
        $approvedDocuments = (clone $query)->where('teacher_status', 'approved')->count();

        $avgDocumentsPerStudent = $totalStudents > 0 ? $totalDocuments / $totalStudents : 0;
        $approvalRate = $totalDocuments > 0 ? ($approvedDocuments / $totalDocuments) * 100 : 0;

        // Calcular tiempo promedio de revisión
        $avgReviewTime = $this->calculateAverageReviewTime($query);

        return [
            'totalStudents' => $totalStudents,
            'avgDocumentsPerStudent' => $avgDocumentsPerStudent,
            'approvalRate' => $approvalRate,
            'avgReviewTime' => $avgReviewTime
        ];
    }

    /**
     * Calcula el tiempo promedio de revisión.
     */
    private function calculateAverageReviewTime($query)
    {
        $reviewedDocuments = (clone $query)
            ->whereNotNull('teacher_status')
            ->where('teacher_status', '!=', 'pending')
            ->get();

        if ($reviewedDocuments->isEmpty()) {
            return 'N/A';
        }

        $totalTime = 0;
        $count = 0;

        foreach ($reviewedDocuments as $document) {
            if ($document->teacher_status && $document->teacher_status !== 'pending') {
                $uploadTime = Carbon::parse($document->created_at);
                $reviewTime = Carbon::parse($document->updated_at);
                $totalTime += $uploadTime->diffInHours($reviewTime);
                $count++;
            }
        }

        if ($count === 0) {
            return 'N/A';
        }

        $avgHours = $totalTime / $count;
        
        if ($avgHours < 24) {
            return round($avgHours, 1) . ' horas';
        } else {
            $avgDays = $avgHours / 24;
            return round($avgDays, 1) . ' días';
        }
    }

    /**
     * Obtiene datos para exportar estadísticas.
     */
    private function getStatisticsData($teacher, $career = null, $period = 'month')
    {
        // Implementar lógica para obtener datos de estadísticas
        // Este método se puede expandir según las necesidades específicas
        return [];
    }

    /**
     * Obtiene datos para exportar comentarios.
     */
    private function getCommentsData($teacher, $career = null, $status = null)
    {
        // Implementar lógica para obtener datos de comentarios
        // Este método se puede expandir según las necesidades específicas
        return [];
    }

    /**
     * Exporta estadísticas a PDF.
     */
    private function exportToPdf($data, $career, $period)
    {
        // Implementar exportación a PDF
        // Se puede usar paquetes como DomPDF o similar
        return response('Exportación a PDF no implementada aún.', 501);
    }

    /**
     * Exporta estadísticas a Excel.
     */
    private function exportToExcel($data, $career, $period)
    {
        // Implementar exportación a Excel
        // Se puede usar paquetes como Maatwebsite Excel o similar
        return response('Exportación a Excel no implementada aún.', 501);
    }

    /**
     * Exporta comentarios a PDF.
     */
    private function exportCommentsToPdf($data, $career, $status)
    {
        // Implementar exportación de comentarios a PDF
        return response('Exportación de comentarios a PDF no implementada aún.', 501);
    }

    /**
     * Exporta comentarios a Excel.
     */
    private function exportCommentsToExcel($data, $career, $status)
    {
        // Implementar exportación de comentarios a Excel
        return response('Exportación de comentarios a Excel no implementada aún.', 501);
    }
}

