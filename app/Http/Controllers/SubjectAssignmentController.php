<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectAssignmentController extends Controller
{
    /**
     * Display a listing of subject assignments.
     */
    public function index(Request $request)
    {
        $query = SubjectTeacher::with(['teacher', 'subject']);

        // Filtros
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('academic_period')) {
            $query->where('academic_period', $request->academic_period);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(15);
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();
        $academicPeriods = $this->getAcademicPeriods();

        return view('admin.subject-assignments.index', compact(
            'assignments', 
            'teachers', 
            'subjects', 
            'academicPeriods'
        ));
    }

    /**
     * Show the form for creating a new subject assignment.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();
        $academicPeriods = $this->getAcademicPeriods();

        return view('admin.subject-assignments.create', compact(
            'teachers', 
            'subjects', 
            'academicPeriods'
        ));
    }

    /**
     * Store a newly created subject assignment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_period' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,pending',
            'max_students' => 'nullable|integer|min:1',
            'comments' => 'nullable|string|max:500',
        ]);

        // Verificar que no exista una asignación duplicada
        $existingAssignment = SubjectTeacher::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_period', $request->academic_period)
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['error' => 'Ya existe una asignación para este profesor, materia y período académico.']);
        }

        try {
            DB::beginTransaction();

            SubjectTeacher::create($request->all());

            DB::commit();

            return redirect()->route('admin.subject-assignments.index')
                ->with('success', 'Asignación de materia creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified subject assignment.
     */
    public function edit(SubjectTeacher $assignment)
    {
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();
        $academicPeriods = $this->getAcademicPeriods();

        return view('admin.subject-assignments.edit', compact(
            'assignment', 
            'teachers', 
            'subjects', 
            'academicPeriods'
        ));
    }

    /**
     * Update the specified subject assignment.
     */
    public function update(Request $request, SubjectTeacher $assignment)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_period' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,pending',
            'max_students' => 'nullable|integer|min:1',
            'comments' => 'nullable|string|max:500',
        ]);

        // Verificar que no exista una asignación duplicada (excluyendo la actual)
        $existingAssignment = SubjectTeacher::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_period', $request->academic_period)
            ->where('id', '!=', $assignment->id)
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['error' => 'Ya existe una asignación para este profesor, materia y período académico.']);
        }

        try {
            DB::beginTransaction();

            $assignment->update($request->all());

            DB::commit();

            return redirect()->route('admin.subject-assignments.index')
                ->with('success', 'Asignación de materia actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified subject assignment.
     */
    public function destroy(SubjectTeacher $assignment)
    {
        try {
            $assignment->delete();
            return redirect()->route('admin.subject-assignments.index')
                ->with('success', 'Asignación de materia eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la asignación: ' . $e->getMessage()]);
        }
    }

    /**
     * Show teacher's subject assignments.
     */
    public function teacherAssignments(Teacher $teacher)
    {
        $assignments = $teacher->subjectAssignments()
            ->with('subject')
            ->orderBy('academic_period', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.subject-assignments.teacher', compact('teacher', 'assignments'));
    }

    /**
     * Show subject's teacher assignments.
     */
    public function subjectAssignments(Subject $subject)
    {
        $assignments = SubjectTeacher::where('subject_id', $subject->id)
            ->with('teacher')
            ->orderBy('academic_period', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.subject-assignments.subject', compact('subject', 'assignments'));
    }

    /**
     * Show the form for bulk assignment.
     */
    public function bulk()
    {
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();
        $academicPeriods = $this->getAcademicPeriods();

        return view('admin.subject-assignments.bulk', compact(
            'teachers', 
            'subjects', 
            'academicPeriods'
        ));
    }

    /**
     * Bulk assign subjects to teachers.
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:teachers,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'academic_period' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,pending',
        ]);

        try {
            DB::beginTransaction();

            $created = 0;
            $skipped = 0;

            foreach ($request->teacher_ids as $teacherId) {
                foreach ($request->subject_ids as $subjectId) {
                    // Verificar si ya existe la asignación
                    $existing = SubjectTeacher::where('teacher_id', $teacherId)
                        ->where('subject_id', $subjectId)
                        ->where('academic_period', $request->academic_period)
                        ->exists();

                    if (!$existing) {
                        SubjectTeacher::create([
                            'teacher_id' => $teacherId,
                            'subject_id' => $subjectId,
                            'academic_period' => $request->academic_period,
                            'status' => $request->status,
                        ]);
                        $created++;
                    } else {
                        $skipped++;
                    }
                }
            }

            DB::commit();

            $message = "Se crearon {$created} asignaciones exitosamente.";
            if ($skipped > 0) {
                $message .= " Se omitieron {$skipped} asignaciones duplicadas.";
            }

            return redirect()->route('admin.subject-assignments.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error en la asignación masiva: ' . $e->getMessage()]);
        }
    }

    /**
     * Get available academic periods.
     */
    private function getAcademicPeriods()
    {
        $currentYear = date('Y');
        $periods = [];

        // Generar períodos para los últimos 2 años y próximos 2 años
        for ($year = $currentYear - 2; $year <= $currentYear + 2; $year++) {
            $periods[] = $year . '-' . ($year + 1);
        }

        return $periods;
    }
} 