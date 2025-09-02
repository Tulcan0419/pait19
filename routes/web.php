<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\{
    StudentLoginController,
    StudentForgotPasswordController,
    StudentResetPasswordController,
    StudentRegisterController,
    TeacherLoginController,
    TeacherRegisterController,
    TeacherForgotPasswordController,
    TeacherResetPasswordController,
    CoordinatorLoginController,
    AdminLoginController
};
use App\Http\Controllers\{
    StudentProfessionalPracticesController,
    TeacherProfessionalPracticesController,
    ProfilePhotoController,
    SettingsController,
    StudentStatisticsController,
    GradeController,
    StudentNotificationController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==================== STUDENT ROUTES ====================
Route::prefix('estudiante')->name('estudiante.')->group(function () {
    // Authentication routes
    Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentLoginController::class, 'login'])->name('login.submit');
    
    // Password reset routes
    Route::get('/password/reset', [StudentForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/password/email', [StudentForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/password/reset/{token}', [StudentResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/password/reset', [StudentResetPasswordController::class, 'reset'])
        ->name('password.update');
    
    // Registration routes
    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [StudentRegisterController::class, 'register'])
        ->name('register.submit');
    
    // Authenticated routes
    Route::middleware('auth:student')->group(function () {
        // Dashboard routes
        Route::get('/dashboard/{career}', function ($career) {
            $student = auth()->guard('student')->user();
            
            if ($student->career !== $career) {
                return redirect()->route('estudiante.dashboard.career', ['career' => $student->career])
                    ->withErrors(['message' => 'No tienes acceso a este dashboard']);
            }
            
            $careerTitles = [
                'mechanical' => 'Mecánica',
                'software' => 'Desarrollo de Software',
                'education' => 'Educación Básica',
            ];
            
            $careerTitle = $careerTitles[$career] ?? ucfirst($career);
            
            return view("auth.student.student.dashboard_{$career}", compact('career', 'careerTitle', 'student'));
        })->name('dashboard.career');
        
        Route::get('/dashboard', function () {
            $student = auth()->guard('student')->user();
            if ($student && $student->career) {
                return redirect()->route('estudiante.dashboard.career', ['career' => $student->career]);
            }
            return view('auth.student.student.dashboard');
        })->name('dashboard');
        
        // Logout route
        Route::post('/logout', [StudentLoginController::class, 'logout'])->name('logout');
        
        // Professional practices routes (solo para estudiantes del 3er semestre en adelante)
        Route::middleware('student.semester')->group(function () {
            Route::resource('practices', StudentProfessionalPracticesController::class)->only(['index', 'store']);
            Route::post('practices/upload', [StudentProfessionalPracticesController::class, 'uploadDocument'])
                ->name('practices.upload');
            Route::get('practices/download/{document}', [StudentProfessionalPracticesController::class, 'downloadDocument'])
                ->name('practices.download');
            Route::delete('practices/{document}', [StudentProfessionalPracticesController::class, 'destroy'])
                ->name('practices.destroy');
            
            // Certificate routes
            Route::get('certificate/generate', [App\Http\Controllers\CertificateController::class, 'generateCertificate'])
                ->name('certificate.generate');
            Route::get('certificate/download/{document}', [App\Http\Controllers\CertificateController::class, 'downloadCertificate'])
                ->name('certificate.download');
            Route::get('certificate/check-eligibility', [App\Http\Controllers\CertificateController::class, 'checkCertificateEligibility'])
                ->name('certificate.check-eligibility');
            
            // Tutor assignment request route
            Route::get('tutor-assignment/request', [StudentProfessionalPracticesController::class, 'requestTutorAssignment'])
                ->name('tutor-assignment.request');
        });
        
        // Profile photo routes
        Route::get('/profile/photo', [ProfilePhotoController::class, 'showUploadForm'])->name('profile.photo');
        Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
        Route::post('/profile/photo/remove', [ProfilePhotoController::class, 'remove'])->name('profile.photo.remove');
        
        // Settings routes
        Route::get('/settings', [SettingsController::class, 'studentSettings'])->name('settings');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        
        // Grade routes
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', [GradeController::class, 'studentView'])->name('index');
            Route::get('/subject/{subject}', [GradeController::class, 'getGradesBySubject'])->name('by_subject');
        });
        
        // Curriculum routes
        Route::prefix('curriculum')->name('curriculum.')->group(function () {
            Route::get('/', [App\Http\Controllers\CurriculumController::class, 'index'])->name('index');
            Route::get('/my-progress', [App\Http\Controllers\CurriculumController::class, 'myProgress'])->name('my-progress');
            Route::get('/subject/{id}', [App\Http\Controllers\CurriculumController::class, 'showSubject'])->name('subject');
            Route::get('/api/subjects', [App\Http\Controllers\CurriculumController::class, 'getSubjectsByYear'])->name('api.subjects');
        });
        
        // Notification routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [StudentNotificationController::class, 'index'])->name('index');
            Route::post('/{notification}/mark-read', [StudentNotificationController::class, 'markAsRead'])->name('mark-read');
            Route::post('/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('/{notification}', [StudentNotificationController::class, 'destroy'])->name('destroy');
            Route::get('/unread-count', [StudentNotificationController::class, 'getUnreadCount'])->name('unread-count');
        });
    });
});

// ==================== TEACHER ROUTES ====================
Route::prefix('profesor')->name('profesor.')->group(function () {
    // Authentication routes
    Route::get('/login', [TeacherLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherLoginController::class, 'login'])->name('login.submit');
    
    // Test route for debugging authentication
    Route::get('/test-auth', [TeacherLoginController::class, 'testAuth'])->name('test-auth');
    
    // Password reset routes
    Route::get('/password/reset', [TeacherForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/password/email', [TeacherForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/password/reset/{token}', [TeacherResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/password/reset', [TeacherResetPasswordController::class, 'reset'])
        ->name('password.update');
    
    // Registration routes
    Route::get('/register', [TeacherRegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [TeacherRegisterController::class, 'register'])
        ->name('register.submit');
    
    // Authenticated routes
    Route::middleware('auth:teacher')->group(function () {
        // Dashboard route
        Route::get('/dashboard', function () {
            $teacher = Auth::guard('teacher')->user();
            $subjects = $teacher->getCurrentSubjects();
            return view('auth.teacher.dashboard', compact('teacher', 'subjects'));
        })->name('dashboard');
        
        // Students summary route
        Route::get('/students-summary', function () {
            $teacher = Auth::guard('teacher')->user();
            $subjects = $teacher->getCurrentSubjects();
            $totalStudents = $subjects->sum(function ($subject) {
                return $subject->students()->count();
            });
            return view('auth.teacher.students_summary', compact('teacher', 'subjects', 'totalStudents'));
        })->name('students.summary');
        
        // My Classes routes
        Route::prefix('my-classes')->name('my-classes.')->group(function () {
            Route::get('/', [App\Http\Controllers\TeacherMyClassesController::class, 'index'])->name('index');
            Route::get('/{subject}', [App\Http\Controllers\TeacherMyClassesController::class, 'show'])->name('show');
            Route::get('/{subject}/statistics', [App\Http\Controllers\TeacherMyClassesController::class, 'statistics'])->name('statistics');
        });
        
        // Professional practices routes (solo para profesores asignados como tutores)
        Route::middleware('teacher.practices.access')->group(function () {
            Route::get('/professional_practices', [TeacherProfessionalPracticesController::class, 'index'])
                ->name('professional_practices.index');
            Route::get('/professional_practices/statistics', [TeacherProfessionalPracticesController::class, 'statistics'])
                ->name('professional_practices.statistics');
            Route::get('/professional_practices/comments', [TeacherProfessionalPracticesController::class, 'comments'])
                ->name('professional_practices.comments');
            Route::post('/professional_practices/{document}/update-status', 
                [TeacherProfessionalPracticesController::class, 'updateStatus'])
                ->name('professional_practices.update_status');
            Route::get('/professional_practices/{document}/download', 
                [TeacherProfessionalPracticesController::class, 'downloadDocument'])
                ->name('professional_practices.download');
        });
        
        // Grade management routes
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', [GradeController::class, 'index'])->name('index');
            Route::get('/create', [GradeController::class, 'create'])->name('create');
            Route::post('/', [GradeController::class, 'store'])->name('store');
            Route::get('/student/{student}', [GradeController::class, 'studentGrades'])->name('student');
            Route::get('/{grade}/edit', [GradeController::class, 'edit'])->name('edit');
            Route::put('/{grade}', [GradeController::class, 'update'])->name('update');
            Route::delete('/{grade}', [GradeController::class, 'destroy'])->name('destroy');
        });
        
        // Progress reports routes
        Route::prefix('progress-reports')->name('progress-reports.')->group(function () {
            Route::get('/', function () {
                return view('auth.teacher.progress_reports');
            })->name('index');
        });
        
        // Profile photo routes
        Route::get('/profile/photo', [ProfilePhotoController::class, 'showUploadForm'])->name('profile.photo');
        Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
        Route::post('/profile/photo/remove', [ProfilePhotoController::class, 'remove'])->name('profile.photo.remove');
        
        // Settings routes
        Route::get('/settings', [SettingsController::class, 'teacherSettings'])->name('settings');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        
        // Logout route
        Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');
    });
});

// ==================== COORDINATOR ROUTES ====================
Route::prefix('coordinador')->name('coordinador.')->group(function () {
    // Authentication routes
    Route::get('/login', [CoordinatorLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CoordinatorLoginController::class, 'login'])->name('login.submit');
    
    // Test route for debugging authentication
    Route::get('/test-auth', [CoordinatorLoginController::class, 'testAuth'])->name('test-auth');
    
    // Authenticated routes
    Route::middleware('auth:coordinador')->group(function () {
        // Dashboard route
        Route::get('/dashboard', [CoordinatorLoginController::class, 'dashboard'])->name('dashboard');
        
        // Logout route
        Route::post('/logout', [CoordinatorLoginController::class, 'logout'])->name('logout');

        // Prácticas preprofesionales para coordinador
        Route::get('professional_practices', [\App\Http\Controllers\CoordinatorProfessionalPracticesController::class, 'index'])
            ->name('professional_practices.index');
        Route::post('professional_practices/{document}/update-status', 
            [\App\Http\Controllers\CoordinatorProfessionalPracticesController::class, 'updateStatus'])
            ->name('professional_practices.update_status');
        Route::get('professional_practices/{document}/download', 
            [\App\Http\Controllers\CoordinatorProfessionalPracticesController::class, 'downloadDocument'])
            ->name('professional_practices.download');
        
        // Certificados para coordinador
        Route::get('certificate/generate/{student}', [App\Http\Controllers\CertificateController::class, 'generateCertificate'])
            ->name('certificate.generate');
        Route::get('certificate/download/{document}', [App\Http\Controllers\CertificateController::class, 'downloadCertificate'])
            ->name('certificate.download');
        
        // Asignación de tutores por estudiante para coordinador
        Route::prefix('student_tutor_assignment')->name('student_tutor_assignment.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CoordinatorStudentTutorAssignmentController::class, 'index'])->name('index');
            Route::post('/{student}/assign', [\App\Http\Controllers\CoordinatorStudentTutorAssignmentController::class, 'assignTutor'])->name('assign');
            Route::post('/{student}/remove', [\App\Http\Controllers\CoordinatorStudentTutorAssignmentController::class, 'removeTutor'])->name('remove');
            Route::get('/report', [\App\Http\Controllers\CoordinatorStudentTutorAssignmentController::class, 'report'])->name('report');
        });
        
        // Profile photo routes
        Route::get('/profile/photo', [ProfilePhotoController::class, 'showUploadForm'])->name('profile.photo');
        Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
        Route::post('/profile/photo/remove', [ProfilePhotoController::class, 'remove'])->name('profile.photo.remove');
        
        // Settings routes
        Route::get('/settings', [SettingsController::class, 'coordinatorSettings'])->name('settings');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        
        // Grade routes
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/report', [GradeController::class, 'coordinatorReport'])->name('report');
        });
    });
});

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    
    // Debug route to check authentication status
    Route::get('/debug-auth', function () {
        return response()->json([
            'admin_check' => Auth::guard('admin')->check(),
            'coordinador_check' => Auth::guard('coordinador')->check(),
            'teacher_check' => Auth::guard('teacher')->check(),
            'student_check' => Auth::guard('student')->check(),
            'admin_user' => Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : null,
            'coordinador_user' => Auth::guard('coordinador')->user() ? Auth::guard('coordinador')->user()->name : null,
        ]);
    })->name('debug-auth');
    
    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        // Dashboard route
        Route::get('/dashboard', function () {
            // Debug: Check which guard is authenticated
            if (Auth::guard('admin')->check()) {
                return view('auth.admin.dashboard');
            } else {
                return redirect('/admin/login')->with('error', 'No autenticado como administrador');
            }
        })->name('dashboard');
        
        // Logout route
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout')->middleware('debug.csrf');
        
        // Gestión de usuarios por el admin
        Route::prefix('dashboard/usuarios')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\AdminUserManagementController::class, 'index'])->name('index');
            Route::get('/crear', [App\Http\Controllers\AdminUserManagementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\AdminUserManagementController::class, 'store'])->name('store');
            Route::get('/{type}/{id}/editar', [App\Http\Controllers\AdminUserManagementController::class, 'edit'])->name('edit');
            Route::put('/{type}/{id}', [App\Http\Controllers\AdminUserManagementController::class, 'update'])->name('update');
            Route::delete('/{type}/{id}', [App\Http\Controllers\AdminUserManagementController::class, 'destroy'])->name('destroy');
        });
        
        // Profile photo routes
        Route::get('/profile/photo', [ProfilePhotoController::class, 'showUploadForm'])->name('profile.photo');
        Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
        Route::post('/profile/photo/remove', [ProfilePhotoController::class, 'remove'])->name('profile.photo.remove');
        
        // Settings routes
        Route::get('/settings', [SettingsController::class, 'adminSettings'])->name('settings');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        
        // Rutas de reportes para admin
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('auth.admin.reports');
            })->name('index');
        });
        
        // Gestión de asignaciones de materias a profesores
        Route::prefix('asignaciones-materias')->name('subject-assignments.')->group(function () {
            Route::get('/', [App\Http\Controllers\SubjectAssignmentController::class, 'index'])->name('index');
            Route::get('/crear', [App\Http\Controllers\SubjectAssignmentController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\SubjectAssignmentController::class, 'store'])->name('store');
            Route::get('/{assignment}/editar', [App\Http\Controllers\SubjectAssignmentController::class, 'edit'])->name('edit');
            Route::put('/{assignment}', [App\Http\Controllers\SubjectAssignmentController::class, 'update'])->name('update');
            Route::delete('/{assignment}', [App\Http\Controllers\SubjectAssignmentController::class, 'destroy'])->name('destroy');
            Route::get('/profesor/{teacher}', [App\Http\Controllers\SubjectAssignmentController::class, 'teacherAssignments'])->name('teacher');
            Route::get('/materia/{subject}', [App\Http\Controllers\SubjectAssignmentController::class, 'subjectAssignments'])->name('subject');
            Route::post('/asignacion-masiva', [App\Http\Controllers\SubjectAssignmentController::class, 'bulkAssign'])->name('bulk');
        });
        
        // Estadísticas de estudiantes
        Route::prefix('estadisticas')->name('statistics.')->group(function () {
            Route::get('/', [StudentStatisticsController::class, 'index'])->name('index');
            Route::get('/carrera/{career}', [StudentStatisticsController::class, 'byCareer'])->name('by_career');
            Route::get('/semestre/{semester}', [StudentStatisticsController::class, 'bySemester'])->name('by_semester');
        });
    });
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return view('errors.404');
});
