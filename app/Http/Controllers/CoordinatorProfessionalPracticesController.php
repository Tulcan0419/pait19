<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DocumentStatusNotification;

class CoordinatorProfessionalPracticesController extends Controller
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
     * Opcionalmente, filtra por una carrera específica y/o por nombre/código de estudiante.
     */
    public function index(Request $request)
    {
        // Obtener todas las carreras disponibles
        $careers = Student::distinct()->pluck('career')->filter()->sort()->values();
        
        // Obtener parámetros de filtrado
        $selectedCareer = $request->get('career');
        $searchTerm = $request->get('search');
        
        if ($selectedCareer) {
            // Obtener estudiantes de la carrera seleccionada con sus documentos
            $studentsQuery = Student::with(['documents.tutor', 'activeTutorAssignment.teacher'])
                ->where('career', $selectedCareer);
            
            // Aplicar filtro de búsqueda si se proporciona
            if ($searchTerm) {
                $studentsQuery->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('student_code', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            $students = $studentsQuery->orderBy('name')->get();
                
            // Agrupar documentos por estudiante
            $studentsWithDocuments = $students->map(function ($student) {
                return [
                    'student' => $student,
                    'documents' => $student->documents()->with('tutor')->latest()->get()
                ];
            });
        } else {
            // Si no se selecciona carrera, mostrar todas las carreras con resumen
            $studentsWithDocuments = collect();
            
            foreach ($careers as $career) {
                $studentsInCareerQuery = Student::with(['documents.tutor', 'activeTutorAssignment.teacher'])
                    ->where('career', $career);
                
                // Aplicar filtro de búsqueda si se proporciona
                if ($searchTerm) {
                    $studentsInCareerQuery->where(function($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('student_code', 'LIKE', "%{$searchTerm}%");
                    });
                }
                
                $studentsInCareer = $studentsInCareerQuery->orderBy('name')->get();
                    
                $totalDocuments = $studentsInCareer->sum(function ($student) {
                    return $student->documents->count();
                });
                
                $studentsWithDocuments->push([
                    'career' => $career,
                    'career_title' => $this->careerTitles[$career] ?? ucfirst($career),
                    'students' => $studentsInCareer,
                    'total_students' => $studentsInCareer->count(),
                    'total_documents' => $totalDocuments
                ]);
            }
        }

        // Preparar las carreras para el selector con nombres en español
        $careersForSelect = $careers->mapWithKeys(function ($career) {
            return [$career => $this->careerTitles[$career] ?? ucfirst($career)];
        });

        return view('auth.admin.professional_practices.index', compact('careersForSelect', 'selectedCareer', 'searchTerm', 'studentsWithDocuments'));
    }

    /**
     * Actualiza el estado de un documento.
     */
    public function updateStatus(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string|max:255',
        ]);

        // El coordinador puede cambiar el estado de cualquier documento
        // Guardar el estado anterior para comparar
        $previousStatus = $document->coordinator_status;

        $document->coordinator_status = $request->status;
        $document->coordinator_comments = $request->comments;
        $document->save();

        // Enviar notificación al estudiante si el estado cambió
        if ($previousStatus !== $request->status) {
            $student = $document->student;
            $student->notify(new DocumentStatusNotification(
                $document, 
                $request->status, 
                'coordinator', 
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
        return Storage::download($document->file_path, $document->file_name);
    }
} 