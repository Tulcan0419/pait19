@extends('layouts.teacher-dashboard')

@section('title', 'Mis Clases - Profesor')

@section('content')
    <!-- Grid de Tarjetas Informativas para Mis Clases -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-book"></i></div>
            <h3>Materias Asignadas</h3>
            <p>Tienes {{ $currentSubjects->count() }} materias activas este semestre.</p>
            <a href="#" class="btn-view">Ver Detalles</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-users"></i></div>
            <h3>Total Estudiantes</h3>
            <p>{{ $totalStudents }} estudiantes en tus materias.</p>
            <a href="{{ route('profesor.students.summary') }}" class="btn-view">Ver Estudiantes</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <h3>Período Actual</h3>
            <p>Estás en el período: {{ $currentPeriod }}</p>
            <a href="#" class="btn-view">Ver Horario</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h3>Gestionar Calificaciones</h3>
            <p>Accede a las calificaciones de tus estudiantes.</p>
            <a href="{{ route('profesor.grades.index') }}" class="btn-view">Gestionar</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-tasks"></i></div>
            <h3>Asignar Tareas</h3>
            <p>Crea y gestiona tareas para tus estudiantes.</p>
            <a href="#" class="btn-view">Crear Tarea</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-history"></i></div>
            <h3>Períodos Anteriores</h3>
            <p>{{ $previousPeriods->count() }} períodos con materias asignadas.</p>
            <a href="#previous-periods" class="btn-view">Ver Historial</a>
        </div>
    </div>

    <!-- Sección de Materias Asignadas -->
    <div class="subjects-section">
        <h2>Materias Asignadas</h2>
        <div class="subjects-grid">
            @forelse($currentSubjects as $subject)
            <div class="subject-card">
                <div class="subject-header">
                    <h3>{{ $subject->name }}</h3>
                    <span class="subject-code">{{ $subject->curricular_unit_name ?? 'N/A' }}</span>
                </div>
                <div class="subject-info">
                    <p><strong>Carrera:</strong> {{ ucfirst($subject->career) }}</p>
                    <p><strong>Semestre:</strong> {{ $subject->semester }}° semestre</p>
                    <p><strong>Créditos:</strong> {{ $subject->credits }}</p>
                    <p><strong>Estudiantes:</strong> {{ $subject->students_count ?? 0 }}</p>
                </div>
                <div class="subject-actions">
                    <a href="{{ route('profesor.students.summary') }}" class="btn-action">Ver Estudiantes</a>
                    <a href="{{ route('profesor.grades.index') }}" class="btn-action">Gestionar Calificaciones</a>
                </div>
            </div>
            @empty
            <div class="no-subjects">
                <p>No tienes materias asignadas para el período actual.</p>
                <p>Contacta al administrador para que te asigne materias.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Sección de Períodos Anteriores -->
    @if($previousPeriods->isNotEmpty())
    <div class="recent-activity" id="previous-periods">
        <h2>Períodos Anteriores</h2>
        <div class="activity-list">
            @foreach($previousPeriods as $period => $periodSubjects)
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="activity-content">
                    <h4>{{ $period }}</h4>
                    <p>{{ $periodSubjects->count() }} materias impartidas</p>
                    <div class="period-subjects-list">
                        @foreach($periodSubjects->take(3) as $subject)
                        <span class="subject-tag">{{ $subject->name }} ({{ $subject->semester }}° sem)</span>
                        @endforeach
                        @if($periodSubjects->count() > 3)
                        <span class="subject-tag">+{{ $periodSubjects->count() - 3 }} más</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script>
    // Scripts específicos para la página de mis clases
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar funcionalidades específicas para mis clases
        console.log('Página de mis clases cargada');
    });
</script>
@endsection 