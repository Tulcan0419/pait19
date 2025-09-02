@extends('layouts.teacher-dashboard')

@section('body-class', 'grades-page')

@section('title', 'Gestión de Calificaciones')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboards/teacher.dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/teacher/grades-dashboard-style.css') }}">
@endsection

@section('content')
    <!-- Alertas -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Grid de Tarjetas Informativas para Calificaciones -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h3>Total de Calificaciones</h3>
            <p>Has registrado {{ $subjects->flatMap->grades->count() }} calificaciones en total.</p>
            <a href="#materias-calificaciones" class="btn-view">Ver Detalles</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-users"></i></div>
            <h3>Estudiantes Evaluados</h3>
            <p>{{ $subjects->flatMap->students->unique('id')->count() }} estudiantes con calificaciones registradas.</p>
            <a href="{{ route('profesor.students.summary') }}" class="btn-view">Ver Estudiantes</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-book-open"></i></div>
            <h3>Materias Activas</h3>
            <p>{{ $subjects->count() }} materias con calificaciones registradas.</p>
            <a href="#materias-calificaciones" class="btn-view">Ver Materias</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-calculator"></i></div>
            <h3>Promedio General</h3>
            <p>Promedio general de todas las calificaciones: {{ round($subjects->flatMap->grades->avg('grade') ?? 0, 2) }}</p>
                            <a href="{{ route('profesor.progress-reports.index') }}" class="btn-view">Ver Estadísticas</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-plus-circle"></i></div>
            <h3>Agregar Calificación</h3>
            <p>Registra una nueva calificación para tus estudiantes.</p>
            <a href="#" class="btn-view" data-bs-toggle="modal" data-bs-target="#addGradeModal">Agregar Calificación</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <h3>Reportes de Calificaciones</h3>
            <p>Genera reportes detallados de rendimiento académico.</p>
                            <a href="{{ route('profesor.progress-reports.index') }}" class="btn-view">Generar Reportes</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-briefcase"></i></div>
            <h3>Revisar Prácticas</h3>
            <p>Revisa y gestiona los documentos de prácticas de los estudiantes.</p>
            <a href="{{ route('profesor.professional_practices.index') }}" class="btn-view">Revisar Prácticas</a>
        </div>
    </div>

    <!-- Sección de Materias con Calificaciones -->
    <div class="subjects-section" id="materias-calificaciones">
        <h2><i class="fas fa-book"></i> Materias con Calificaciones</h2>
        <div class="subjects-grid">
            @foreach($subjects as $subject)
            <div class="subject-card">
                <div class="subject-header">
                    <h3><i class="fas fa-graduation-cap"></i> {{ $subject->name }}</h3>
                    <span class="subject-code">{{ $subject->code }}</span>
                </div>
                <div class="subject-info">
                    <p><strong>Estudiantes:</strong> {{ $subject->students->count() }}</p>
                    <p><strong>Calificaciones:</strong> {{ $subject->grades->count() }}</p>
                    <p><strong>Promedio:</strong> {{ round($subject->grades->avg('grade') ?? 0, 2) }}</p>
                </div>
                <div class="subject-actions">
                    <a href="{{ route('profesor.students.summary') }}" class="btn-action">Ver Estudiantes</a>
                    <a href="#" class="btn-action" data-bs-toggle="modal" data-bs-target="#addGradeModal" data-subject="{{ $subject->id }}">Agregar Calificación</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Sección de Actividad Reciente de Calificaciones -->
    <div class="recent-activity">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="fas fa-clock"></i> Actividad Reciente de Calificaciones</h2>
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-home"></i> Volver al Dashboard
            </a>
        </div>
        <div class="activity-list">
            @forelse($subjects->flatMap->grades->sortByDesc('created_at')->take(5) as $grade)
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="activity-content">
                    <h4>Calificación registrada</h4>
                    <p>{{ $grade->student->name }} - {{ $grade->subject->name }}: {{ $grade->grade }}/10</p>
                    <span class="activity-time">{{ $grade->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="activity-content">
                    <h4>No hay calificaciones recientes</h4>
                    <p>Comienza registrando calificaciones para tus estudiantes.</p>
                    <span class="activity-time">Sin actividad</span>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal para Agregar Calificación -->
    <div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGradeModalLabel">
                        <i class="fas fa-plus-circle"></i> Agregar Nueva Calificación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profesor.grades.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Estudiante</label>
                                    <select class="form-select" id="student_id" name="student_id" required>
                                        <option value="">Seleccionar estudiante</option>
                                        @foreach($subjects->flatMap->students->unique('id') as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->career }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject_id" class="form-label">Materia</label>
                                    <select class="form-select" id="subject_id" name="subject_id" required>
                                        <option value="">Seleccionar materia</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="grade" class="form-label">Calificación</label>
                                    <input type="number" class="form-control" id="grade" name="grade" min="0" max="10" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipo de Evaluación</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="exam">Examen</option>
                                        <option value="homework">Tarea</option>
                                        <option value="project">Proyecto</option>
                                        <option value="participation">Participación</option>
                                        <option value="final">Final</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Título de la Evaluación</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="comments" class="form-label">Comentarios (opcional)</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evaluation_date" class="form-label">Fecha de Evaluación</label>
                            <input type="date" class="form-control" id="evaluation_date" name="evaluation_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer fecha actual por defecto
        document.getElementById('evaluation_date').valueAsDate = new Date();
        
        // Manejar el modal de agregar calificación
        const addGradeModal = document.getElementById('addGradeModal');
        if (addGradeModal) {
            addGradeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const subjectId = button.getAttribute('data-subject');
                if (subjectId) {
                    document.getElementById('subject_id').value = subjectId;
                }
            });
        }
        
        // Validación del formulario
        const form = addGradeModal.querySelector('form');
        form.addEventListener('submit', function(e) {
            const grade = document.getElementById('grade').value;
            if (grade < 0 || grade > 10) {
                e.preventDefault();
                alert('La calificación debe estar entre 0 y 10');
                return false;
            }
        });
    });
</script>
@endsection 