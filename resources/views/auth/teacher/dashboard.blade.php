@extends('layouts.teacher-dashboard')

@section('title', 'Dashboard Profesor - Tecnológico Traversari - ISTPET')

@section('content')
    <!-- Grid de Tarjetas Informativas para Profesor -->
    <div class="dashboard-grid">
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-briefcase"></i></div>
            <h3>Revisar Prácticas</h3>
            <p>Revisa y gestiona los documentos de prácticas de los estudiantes.</p>
            <a href="{{ route('profesor.professional_practices.index') }}" class="btn-view">Revisar Prácticas</a>
        </div>
    </div>

    <!-- Sección de Materias Impartidas -->
    

    <!-- Sección de Actividad Reciente -->
@endsection

@section('scripts')
<script>
    // Scripts específicos para el dashboard de profesores
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar funcionalidades específicas para profesores
        console.log('Dashboard de profesor cargado');
    });
</script>
@endsection