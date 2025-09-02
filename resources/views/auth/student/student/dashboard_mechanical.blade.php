@extends('layouts.student-dashboard')

@section('title', 'Dashboard Estudiante de Mecánica - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas -->
    <div class="dashboard-grid">
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-file-upload"></i></div>
            <h3>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</h3>
            <p>Sube tus documentos de {{ Auth::guard('student')->user()->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }} aquí.</p>
            <a href="{{ route('estudiante.practices.index') }}" class="btn-view">Subir Documentos</a>
        </div>
    </div>

    <!-- Sección de Actividad Reciente -->
    
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de estudiantes de mecánica
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard de estudiante de mecánica cargado');
    });
</script>
@endsection