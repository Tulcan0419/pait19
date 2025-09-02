@extends('layouts.teacher-dashboard')

@section('title', 'Calificaciones del Estudiante')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/teacher/grades.css') }}">
<style>
    .student-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .student-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    }
    
    .student-info {
        position: relative;
        z-index: 1;
    }
    
    .grade-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    
    .grade-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .grade-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1rem;
        font-weight: 600;
    }
    
    .grade-value {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        padding: 1rem;
    }
    
    .grade-value.excellent {
        color: #28a745;
    }
    
    .grade-value.good {
        color: #ffc107;
    }
    
    .grade-value.poor {
        color: #dc3545;
    }
    
    .subject-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.25rem;
        color: white;
    }
    
    .grade-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .grade-timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-left: 1rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 1.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #667eea;
    }
    
    .grade-badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .grade-badge.excellent {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .grade-badge.good {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    
    .grade-badge.poor {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
    }
    
    .progress-chart {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
        margin: 0.5rem 0;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .progress-fill.excellent {
        background: linear-gradient(90deg, #28a745, #20c997);
    }
    
    .progress-fill.good {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }
    
    .progress-fill.poor {
        background: linear-gradient(90deg, #dc3545, #e83e8c);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header del estudiante -->
    <div class="student-header">
        <div class="student-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-user-graduate me-2"></i>
                        {{ $student->name }}
                    </h2>
                    <p class="mb-1">
                        <strong>Código:</strong> {{ $student->student_code }}
                    </p>
                    <p class="mb-1">
                        <strong>Carrera:</strong> {{ ucfirst($student->career) }}
                    </p>
                    <p class="mb-0">
                        <strong>Semestre:</strong> {{ $student->semester }}°
                    </p>
                </div>
                <div class="text-end">
                    <div class="grade-value {{ $generalAverage >= 8 ? 'excellent' : ($generalAverage >= 6 ? 'good' : 'poor') }}">
                        {{ round($generalAverage, 2) }}/10
                    </div>
                    <small>Promedio General</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas por materia -->
    <div class="subject-stats">
        @foreach($subjects as $subject)
        @php
            $subjectGrades = $grades->where('subject_id', $subject->id);
            $subjectAverage = $averages[$subject->id] ?? 0;
            $gradeClass = $subjectAverage >= 8 ? 'excellent' : ($subjectAverage >= 6 ? 'good' : 'poor');
        @endphp
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-book"></i>
            </div>
            <h5 class="mb-1">{{ $subject->name }}</h5>
            <div class="grade-badge {{ $gradeClass }} mb-2">
                {{ round($subjectAverage, 2) }}/10
            </div>
            <small class="text-muted">{{ $subjectGrades->count() }} calificaciones</small>
        </div>
        @endforeach
    </div>

    <!-- Gráfico de progreso -->
    <div class="progress-chart">
        <h5 class="mb-3">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Progreso Académico por Materia
        </h5>
        @foreach($subjects as $subject)
        @php
            $subjectAverage = $averages[$subject->id] ?? 0;
            $percentage = ($subjectAverage / 10) * 100;
            $progressClass = $subjectAverage >= 8 ? 'excellent' : ($subjectAverage >= 6 ? 'good' : 'poor');
        @endphp
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold">{{ $subject->name }}</span>
                <span class="text-muted">{{ round($subjectAverage, 2) }}/10</span>
            </div>
            <div class="progress-bar-custom">
                <div class="progress-fill {{ $progressClass }}" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Timeline de calificaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Historial de Calificaciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="grade-timeline">
                        @forelse($grades as $grade)
                        @php
                            $gradeClass = $grade->grade >= 8 ? 'excellent' : ($grade->grade >= 6 ? 'good' : 'poor');
                        @endphp
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="grade-badge {{ $gradeClass }} me-3">
                                            {{ $grade->grade }}/10
                                        </span>
                                        <h6 class="mb-0">{{ $grade->title }}</h6>
                                    </div>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-book me-1"></i>
                                        {{ $grade->subject->name }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $grade->evaluation_date->format('d/m/Y') }}
                                    </p>
                                    @if($grade->comments)
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-comment me-2"></i>
                                        <strong>Comentario:</strong> {{ $grade->comments }}
                                    </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary">{{ ucfirst($grade->type) }}</span>
                                    <br>
                                    <small class="text-muted">{{ $grade->teacher->name }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted fa-3x mb-3"></i>
                            <p class="text-muted">No hay calificaciones registradas para este estudiante</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-3">Acciones Rápidas</h5>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <button class="btn btn-primary" onclick="addGrade({{ $student->id }})">
                            <i class="fas fa-plus me-2"></i>Agregar Calificación
                        </button>
                        <button class="btn btn-success" onclick="exportStudentGrades({{ $student->id }})">
                            <i class="fas fa-download me-2"></i>Exportar Calificaciones
                        </button>
                        <button class="btn btn-info" onclick="generateReport({{ $student->id }})">
                            <i class="fas fa-file-alt me-2"></i>Generar Reporte
                        </button>
                        <a href="{{ route('profesor.grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar calificación -->
<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Agregar Calificación para {{ $student->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profesor.grades.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_id" class="form-label fw-bold">
                                    <i class="fas fa-book text-primary me-2"></i>Materia
                                </label>
                                <select name="subject_id" id="subject_id" class="form-select" required>
                                    <option value="">Seleccionar materia</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="grade" class="form-label fw-bold">
                                    <i class="fas fa-star text-warning me-2"></i>Calificación
                                </label>
                                <div class="input-group">
                                    <input type="number" name="grade" id="grade" class="form-control" 
                                           min="0" max="10" step="0.01" required>
                                    <span class="input-group-text">/10</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label fw-bold">
                                    <i class="fas fa-tasks text-primary me-2"></i>Tipo de Evaluación
                                </label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="exam">Examen</option>
                                    <option value="homework">Tarea</option>
                                    <option value="project">Proyecto</option>
                                    <option value="participation">Participación</option>
                                    <option value="final">Final</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evaluation_date" class="form-label fw-bold">
                                    <i class="fas fa-calendar text-primary me-2"></i>Fecha de Evaluación
                                </label>
                                <input type="date" name="evaluation_date" id="evaluation_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-heading text-primary me-2"></i>Título de la Evaluación
                        </label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comments" class="form-label fw-bold">
                            <i class="fas fa-comment text-primary me-2"></i>Comentarios
                        </label>
                        <textarea name="comments" id="comments" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Calificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addGrade(studentId) {
    const modal = new bootstrap.Modal(document.getElementById('addGradeModal'));
    modal.show();
    
    // Establecer fecha actual por defecto
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('evaluation_date').value = today;
}

function exportStudentGrades(studentId) {
    // Implementar exportación de calificaciones del estudiante
    alert('Función de exportación en desarrollo');
}

function generateReport(studentId) {
    // Implementar generación de reporte
    alert('Función de generación de reporte en desarrollo');
}

// Animaciones de entrada
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.stat-card, .timeline-item, .progress-chart').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endpush 