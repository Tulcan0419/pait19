<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
|
| These routes are for testing purposes only. They should not be used in production.
|
*/

// ==================== TEST ROUTES ====================
Route::prefix('test')->name('test.')->group(function () {
    Route::get('/assets', function () {
        return view('test-assets');
    })->name('assets');

    Route::get('/login', function () {
        return view('test-login');
    })->name('login');

    Route::get('/teacher-login', function () {
        return view('test-teacher-login');
    })->name('teacher.login');

    Route::get('/admin-users', function () {
        return view('test-admin-users');
    })->name('admin.users');

    Route::get('/admin-edit-user', function () {
        return view('test-admin-edit-user');
    })->name('admin.edit.user');

    Route::get('/admin-statistics', function () {
        return view('test-admin-statistics');
    })->name('admin.statistics');

    Route::get('/menu-toggle', function () {
        return view('test-menu-toggle');
    })->name('menu.toggle');

    Route::get('/student-practices', function () {
        return view('test-student-practices');
    })->name('student.practices');

    Route::get('/table-responsive', function () {
        return view('test-table-responsive');
    })->name('table.responsive');

    Route::get('/teacher-students-summary', function () {
        return view('test-teacher-students-summary');
    })->name('teacher.students.summary');

    Route::get('/logo-placeholder', function () {
        return view('test-logo-placeholder');
    })->name('logo.placeholder');

    Route::get('/logout', function () {
        return view('test-logout');
    })->name('logout');

    Route::get('/csrf', function () {
        return view('test-csrf');
    })->name('csrf');

    Route::get('/professional-practices', function () {
        return redirect()->route('profesor.professional_practices.index');
    })->name('professional.practices');

    Route::get('/professional-practices-no-auth', function () {
        try {
            // Simular un profesor para testing
            $teacher = new \App\Models\Teacher();
            $teacher->id = 1;
            $teacher->name = 'Profesor Test';
            
            // Obtener carreras que tienen estudiantes asignados al profesor como tutor
            $careers = \App\Models\Student::whereHas('tutorAssignments', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)->where('status', 'active');
            })->distinct()->pluck('career')->filter()->sort()->values();
            
            $hasAssignedStudents = $careers->count() > 0;
            
            return response()->json([
                'success' => true,
                'teacher_id' => $teacher->id,
                'careers' => $careers->toArray(),
                'hasAssignedStudents' => $hasAssignedStudents,
                'message' => 'Controlador funcionando correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('professional.practices.no.auth');

    Route::get('/professional-practices-simple', function () {
        try {
            // Ruta simple para probar el controlador sin middleware
            $controller = new \App\Http\Controllers\TeacherProfessionalPracticesController();
            $request = new \Illuminate\Http\Request();
            
            // Simular un profesor autenticado
            Auth::guard('teacher')->login(\App\Models\Teacher::find(1));
            
            $response = $controller->index($request);
            
            return $response;
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('professional.practices.simple');

    Route::get('/admin-reports', function () {
        return view('test-admin-reports');
    })->name('admin.reports');

    Route::get('/teacher-progress-reports', function () {
        return view('test-teacher-progress-reports');
    })->name('teacher.progress.reports');

    Route::get('/sidebar-toggle', function () {
        // Simular un usuario estudiante para el test
        if (!auth()->guard('student')->check()) {
            // Crear un usuario temporal para el test
            $student = new \App\Models\Student();
            $student->id = 1;
            $student->name = 'Estudiante Test';
            $student->email = 'test@example.com';
            $student->semester = 3;
            auth()->guard('student')->setUser($student);
        }
        return view('test-sidebar-toggle');
    })->name('sidebar.toggle');

    Route::get('/all-sidebars', function () {
        return view('test-all-sidebars');
    })->name('all.sidebars');
    
    // Ruta de prueba para verificar CSRF del coordinador
    Route::get('/coordinator-csrf-test', function () {
        return response()->json([
            'csrf_token' => csrf_token(),
            'session_id' => session()->getId(),
            'session_driver' => config('session.driver'),
            'csrf_field' => csrf_field()
        ]);
    })->name('coordinator.csrf.test');
});
