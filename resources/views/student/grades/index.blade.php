@extends('layouts.student-dashboard')

@section('title', 'Mis Calificaciones')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/student/grades.css') }}">
@endpush

@section('content')
<div class="container-fluid grades-container">
    <!-- Mensaje informativo -->
    @if(session('info'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Header principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                            <i class="fas fa-graduation-cap text-white fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="mb-0">Bienvenido a tu Dashboard Académico</h4>
                        <p class="text-muted mb-0">Seguimiento de tu rendimiento académico</p>
                    </div>
                </div>
                <div class="page-title-right">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-chart-line me-1"></i>
                                Promedio General: {{ round($generalAverage, 2) }}/10
                            </span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportGrades()">
                                    <i class="fas fa-download me-2"></i>Exportar PDF
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="printGrades()">
                                    <i class="fas fa-print me-2"></i>Imprimir
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de calificaciones -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Calificaciones</p>
                            <h4 class="mb-0" data-count="{{ $grades->count() }}">0</h4>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                Último mes: {{ $grades->where('evaluation_date', '>=', now()->subMonth())->count() }}
                            </small>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Promedio General</p>
                            <h4 class="mb-0" data-count="{{ round($generalAverage, 2) }}">0</h4>
                            <small class="text-{{ $generalAverage >= 7 ? 'success' : ($generalAverage >= 5 ? 'warning' : 'danger') }}">
                                <i class="fas fa-{{ $generalAverage >= 7 ? 'star' : ($generalAverage >= 5 ? 'check' : 'times') }} me-1"></i>
                                {{ $generalAverage >= 7 ? 'Excelente' : ($generalAverage >= 5 ? 'Aprobado' : 'Necesita mejorar') }}
                            </small>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Materias Activas</p>
                            <h4 class="mb-0" data-count="{{ $totalSubjects ?? 0 }}">0</h4>
                            <small class="text-info">
                                <i class="fas fa-book me-1"></i>
                                Con calificaciones registradas
                            </small>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Última Calificación</p>
                            <h4 class="mb-0">{{ $grades->first() ? $grades->first()->evaluation_date->format('d/m/Y') : 'N/A' }}</h4>
                            <small class="text-warning">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $grades->first() ? $grades->first()->evaluation_date->diffForHumans() : 'Sin registros' }}
                            </small>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Mis Materias -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2" style="color: var(--orange);"></i>
                    Mis Materias
                </h5>
                <a href="#" class="text-decoration-none" style="color: var(--orange);">
                    Ver todas las materias <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de materias -->
    @if($subjects->count() > 0)
    <div class="row mb-5" id="subjectsContainer">
        @foreach($subjects as $subject)
        @php
            $subjectGrades = $grades->where('subject_id', $subject->id);
            $subjectAverage = $averages[$subject->id] ?? 0;
            $excellentGrades = $subjectGrades->where('grade', '>=', 7)->count();
            $totalSubjectGrades = $subjectGrades->count();
            $excellenceRate = $totalSubjectGrades > 0 ? round(($excellentGrades / $totalSubjectGrades) * 100, 1) : 0;
            
            // Colores para las tarjetas
            $colors = ['var(--yellow)', 'var(--light-purple)', 'var(--orange)', '#4facfe', '#43e97b'];
            $color = $colors[$loop->index % count($colors)];
        @endphp
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4 subject-card" data-subject="{{ $subject->name }}">
            <div class="card h-100 course-card" style="border-left: 4px solid {{ $color }};">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge mb-2" style="background: {{ $color }}; color: white; font-size: 0.8rem;">
                                {{ $subject->career ?? 'Académico' }}
                            </span>
                            <h6 class="card-title mb-1" style="font-weight: 600; color: var(--black);">
                                {{ $subject->name }}
                            </h6>
                        </div>
                        <div class="text-end">
                            <div class="progress mb-2" style="height: 8px; width: 80px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $excellenceRate }}%; background: {{ $color }};"
                                     aria-valuenow="{{ $excellenceRate }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ $excellenceRate }}% excelencia</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge fs-6" style="background: {{ $color }}; color: white;">
                                <i class="fas fa-{{ $subjectAverage >= 7 ? 'star' : ($subjectAverage >= 5 ? 'check' : 'times') }} me-1"></i>
                                {{ round($subjectAverage, 2) }}/10
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm rounded-circle me-2" style="background: {{ $color }}; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-white fw-bold" style="font-size: 0.8rem;">{{ substr($subject->name, 0, 1) }}</span>
                            </div>
                            <div class="avatar-sm rounded-circle me-2" style="background: var(--gray-300); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted fw-bold" style="font-size: 0.8rem;">+{{ $totalSubjectGrades }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn w-100" style="background: {{ $color }}; color: white; border: none; border-radius: var(--radius-md); font-weight: 600;">
                        Continuar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Sección: Próximas Evaluaciones -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2" style="color: var(--orange);"></i>
                    Próximas Evaluaciones
                </h5>
                <a href="#" class="text-decoration-none" style="color: var(--orange);">
                    Ver todas las evaluaciones <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de próximas evaluaciones -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Evaluación</th>
                                    <th>Materia</th>
                                    <th>Fecha</th>
                                    <th>Profesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades->take(3) as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-{{ $grade->type === 'exam' ? 'file-alt' : ($grade->type === 'final' ? 'trophy' : 'tasks') }}" 
                                                   style="color: var(--orange);"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $grade->title }}</strong>
                                                <br><small class="text-muted">{{ ucfirst($grade->type) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $grade->subject->name }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $grade->evaluation_date->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $grade->evaluation_date->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle me-2" style="background: var(--orange); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-white fw-bold" style="font-size: 0.8rem;">{{ substr($grade->teacher->name, 0, 1) }}</span>
                                            </div>
                                            <span class="fw-medium">{{ $grade->teacher->name }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>No hay evaluaciones próximas</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Detalle de Calificaciones -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt me-2" style="color: var(--orange);"></i>
                    Detalle de Calificaciones
                </h5>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="searchGrades" placeholder="Buscar calificaciones..." style="width: 250px;">
                    <select class="form-select" id="filterSubject" style="width: 200px;">
                        <option value="">Todas las materias</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de calificaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover grades-table">
                            <thead>
                                <tr>
                                    <th>Evaluación</th>
                                    <th>Materia</th>
                                    <th>Tipo</th>
                                    <th>Calificación</th>
                                    <th>Fecha</th>
                                    <th>Profesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                <tr class="grade-row" 
                                    data-subject="{{ $grade->subject->name }}"
                                    data-type="{{ $grade->type }}"
                                    data-title="{{ strtolower($grade->title) }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-{{ $grade->type === 'exam' ? 'file-alt' : ($grade->type === 'final' ? 'trophy' : 'tasks') }}" style="color: var(--orange);"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $grade->title }}</strong>
                                                @if($grade->comments)
                                                <br><small class="text-muted">{{ Str::limit($grade->comments, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: var(--light-purple); color: white;">
                                            {{ $grade->subject->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: var(--gray-500); color: white;">
                                            <i class="fas fa-{{ $grade->type === 'exam' ? 'file-alt' : ($grade->type === 'final' ? 'trophy' : 'tasks') }} me-1"></i>
                                            {{ ucfirst($grade->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge fs-6" style="background: {{ $grade->grade >= 7 ? 'var(--gradient-green)' : ($grade->grade >= 5 ? 'var(--gradient-yellow)' : 'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%)') }}; color: white;">
                                            <i class="fas fa-{{ $grade->grade >= 7 ? 'star' : ($grade->grade >= 5 ? 'check' : 'times') }} me-1"></i>
                                            {{ $grade->grade }}/10
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $grade->evaluation_date->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $grade->evaluation_date->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle me-2" style="background: var(--orange); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-white fw-bold" style="font-size: 0.8rem;">{{ substr($grade->teacher->name, 0, 1) }}</span>
                                            </div>
                                            <span class="fw-medium">{{ $grade->teacher->name }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <p>No tienes calificaciones registradas</p>
                        <small>Las calificaciones aparecerán aquí una vez que tus profesores las registren</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Animación de contadores
function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');
    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = current.toFixed(2);
        }, 16);
    });
}

// Búsqueda y filtros
document.getElementById('searchGrades').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.grade-row');
    
    rows.forEach(row => {
        const title = row.getAttribute('data-title');
        const subject = row.getAttribute('data-subject').toLowerCase();
        const type = row.getAttribute('data-type');
        
        if (title.includes(searchTerm) || subject.includes(searchTerm) || type.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('filterSubject').addEventListener('change', function() {
    const selectedSubject = this.value;
    const rows = document.querySelectorAll('.grade-row');
    
    rows.forEach(row => {
        const subject = row.getAttribute('data-subject');
        if (!selectedSubject || subject === selectedSubject) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Funciones de exportación
function exportGrades() {
    // Implementar exportación a PDF
    alert('Función de exportación en desarrollo');
}

function printGrades() {
    window.print();
}

// Inicializar animaciones
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});
</script>
@endpush
@endsection 