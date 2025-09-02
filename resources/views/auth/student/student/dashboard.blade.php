@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <h3>Cursos Inscritos</h3>
            <p>Tienes 5 cursos activos este semestre.</p>
            <a href="#" class="btn-view">Ver Cursos</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
            <h3>Tareas Pendientes</h3>
            <p>3 tareas por entregar esta semana.</p>
            <a href="#" class="btn-view">Ver Tareas</a>
        </div>
        <div class="card-item">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h3>Promedio General</h3>
            <p>Tu promedio actual es de 8.5/10.</p>
            <a href="#" class="btn-view">Ver Calificaciones</a>
        </div>
        @if(Auth::guard('student')->user()->semester >= 3)
        <div class="card-item">
            <div class="icon"><i class="fas fa-file-upload"></i></div>
            <h3>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</h3>
            <p>Sube tus documentos de {{ Auth::guard('student')->user()->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }} aquí.</p>
            <a href="{{ route('estudiante.practices.index') }}" class="btn-view">Subir Documentos</a>
        </div>
        @else
        <div class="card-item disabled-card">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <h3>Prácticas Preprofesionales</h3>
            <p>Disponible a partir del 3° semestre. Tu semestre actual: {{ Auth::guard('student')->user()->semester }}°</p>
            <span class="btn-disabled">No Disponible</span>
        </div>
        @endif
    </div>

    <!-- Sección de Actividad Reciente -->
    <div class="recent-activity">
        <h2>Actividad Reciente</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="activity-content">
                    <h4>Nueva tarea asignada</h4>
                    <p>Matemáticas - Ejercicios de Álgebra</p>
                    <span class="activity-time">Hace 2 horas</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="activity-content">
                    <h4>Calificación actualizada</h4>
                    <p>Física - Examen Parcial: 9.2/10</p>
                    <span class="activity-time">Hace 1 día</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="activity-content">
                    <h4>Nuevo anuncio</h4>
                    <p>Reunión de padres de familia - Viernes 3:00 PM</p>
                    <span class="activity-time">Hace 2 días</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar funcionalidades específicas para estudiantes
        console.log('Dashboard de estudiante cargado');
    });
</script>
@endsection