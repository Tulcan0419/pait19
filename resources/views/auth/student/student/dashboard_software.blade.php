@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante de Software - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-laptop-code"></i></div>
            <h3>Asignaturas del {{ $student->semester ?? Auth::guard('student')->user()->semester }}° Semestre</h3>
            @php
                $currentStudent = $student ?? Auth::guard('student')->user();
                $currentSemesterSubjects = \App\Models\Subject::byCareer($currentStudent->career)
                    ->bySemester($currentStudent->semester)
                    ->active()
                    ->get();
            @endphp
            <p>Tienes {{ $currentSemesterSubjects->count() }} asignaturas en tu semestre actual.</p>
            <a href="{{ route('estudiante.curriculum.index') }}" class="btn-view">Ver Malla Curricular</a>
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
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="activity-content">
                    <h4>Proyecto entregado</h4>
                    <p>Sistema de Gestión Escolar - Entregado el 10 de Julio</p>
                    <span class="activity-time">Hace 1 día</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-upload"></i>
                </div>
                <div class="activity-content">
                    <h4>Documento subido</h4>
                    <p>Proyecto Final de Programación - Entregado el 12 de Julio</p>
                    <span class="activity-time">Hace 2 días</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="activity-content">
                    <h4>Nuevo mensaje</h4>
                    <p>Profesor Juan Pérez - Sobre la clase de Física</p>
                    <span class="activity-time">Hace 3 días</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes de software
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard de estudiante de software cargado');
    });
</script>
@endsection