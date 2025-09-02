<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Mostrar todas las calificaciones (para profesores)
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjects = $teacher->subjects()->with(['students', 'grades'])->get();
        
        return view('teacher.grades.index', compact('subjects'));
    }

    /**
     * Mostrar calificaciones de un estudiante específico
     */
    public function studentGrades($studentId)
    {
        $student = Student::findOrFail($studentId);
        $grades = Grade::where('student_id', $studentId)
            ->with(['subject', 'teacher'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        $subjects = Subject::all();
        $averages = [];

        foreach ($subjects as $subject) {
            $averages[$subject->id] = Grade::getAverageByStudentAndSubject($studentId, $subject->id);
        }

        return view('teacher.grades.student', compact('student', 'grades', 'subjects', 'averages'));
    }

    /**
     * Mostrar formulario para crear calificación
     */
    public function create()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjects = $teacher->subjects()->with('students')->get();
        
        return view('teacher.grades.create', compact('subjects'));
    }

    /**
     * Guardar nueva calificación
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:10',
            'type' => 'required|in:exam,homework,project,participation,final',
            'title' => 'required|string|max:255',
            'comments' => 'nullable|string',
            'evaluation_date' => 'required|date'
        ]);

        // Verificar que el profesor tenga acceso a la materia
        $teacher = Auth::guard('teacher')->user();
        $hasAccess = $teacher->subjects()->where('subjects.id', $request->subject_id)->exists();
        
        if (!$hasAccess) {
            return redirect()->back()->with('error', 'No tienes acceso a esta materia');
        }

        $grade = Grade::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $teacher->id,
            'grade' => $request->grade,
            'type' => $request->type,
            'title' => $request->title,
            'comments' => $request->comments,
            'evaluation_date' => $request->evaluation_date
        ]);

        return redirect()->route('teacher.grades.index')->with('success', 'Calificación registrada exitosamente');
    }

    /**
     * Mostrar formulario para editar calificación
     */
    public function edit(Grade $grade)
    {
        // Verificar que el profesor sea el propietario de la calificación
        if ($grade->teacher_id !== Auth::guard('teacher')->id()) {
            return redirect()->back()->with('error', 'No tienes permisos para editar esta calificación');
        }

        return view('teacher.grades.edit', compact('grade'));
    }

    /**
     * Actualizar calificación
     */
    public function update(Request $request, Grade $grade)
    {
        // Verificar que el profesor sea el propietario de la calificación
        if ($grade->teacher_id !== Auth::guard('teacher')->id()) {
            return redirect()->back()->with('error', 'No tienes permisos para editar esta calificación');
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:10',
            'comments' => 'nullable|string'
        ]);

        $grade->update([
            'grade' => $request->grade,
            'comments' => $request->comments
        ]);

        return redirect()->route('teacher.grades.index')->with('success', 'Calificación actualizada exitosamente');
    }

    /**
     * Eliminar calificación
     */
    public function destroy(Grade $grade)
    {
        // Verificar que el profesor sea el propietario de la calificación
        if ($grade->teacher_id !== Auth::guard('teacher')->id()) {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar esta calificación');
        }

        $grade->delete();
        return redirect()->route('teacher.grades.index')->with('success', 'Calificación eliminada exitosamente');
    }

    /**
     * Mostrar calificaciones para estudiantes
     */
    public function studentView()
    {
        $student = Auth::guard('student')->user();
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        // Obtener solo las materias en las que el estudiante tiene calificaciones
        $subjects = $grades->pluck('subject')->unique('id')->sortBy('name');
        $averages = [];

        foreach ($subjects as $subject) {
            $averages[$subject->id] = Grade::getAverageByStudentAndSubject($student->id, $subject->id);
        }

        // Contar materias únicas con calificaciones
        $totalSubjects = $subjects->count();

        $generalAverage = Grade::getGeneralAverage($student->id);

        // Si no hay calificaciones, mostrar mensaje informativo
        if ($grades->isEmpty()) {
            return view('student.grades.index', compact('grades', 'subjects', 'averages', 'generalAverage', 'totalSubjects'))
                ->with('info', 'Aún no tienes calificaciones registradas. Las calificaciones aparecerán aquí una vez que tus profesores las ingresen.');
        }

        return view('student.grades.index', compact('grades', 'subjects', 'averages', 'generalAverage', 'totalSubjects'));
    }

    /**
     * Obtener calificaciones por materia (AJAX)
     */
    public function getGradesBySubject(Request $request, $subjectId)
    {
        $student = Auth::guard('student')->user();
        
        $grades = Grade::where('student_id', $student->id)
            ->where('subject_id', $subjectId)
            ->with(['teacher'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        $average = $grades->avg('grade');

        return response()->json([
            'success' => true,
            'grades' => $grades,
            'average' => round($average, 2)
        ]);
    }

    /**
     * Reporte de calificaciones para coordinadores
     */
    public function coordinatorReport()
    {
        $grades = Grade::with(['student', 'subject', 'teacher'])
            ->orderBy('evaluation_date', 'desc')
            ->get();

        $statistics = [
            'total_grades' => $grades->count(),
            'average_grade' => $grades->avg('grade'),
            'highest_grade' => $grades->max('grade'),
            'lowest_grade' => $grades->min('grade'),
        ];

        return view('coordinator.grades.report', compact('grades', 'statistics'));
    }
}
