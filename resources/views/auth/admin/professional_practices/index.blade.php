@extends('layouts.coordinator-dashboard')

@section('title', 'Gestión de Prácticas Preprofesionales - Coordinador')

@section('content')
<link rel="stylesheet" href="{{ asset('css/coordinator/professional_practices.css') }}">

<div class="coordinator-practices-container">
    <!-- Header Principal -->
    <div class="practices-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="header-text">
                <h1>Gestión de Prácticas Preprofesionales</h1>
                <p>Revisa, gestiona y supervisa los documentos de prácticas organizados por carreras</p>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Filtro de Carreras -->
    <div class="career-filter-section">
        <div class="career-filter-card">
            <div class="filter-header">
                <i class="fas fa-graduation-cap"></i>
                <h3>Filtrar por Carrera</h3>
            </div>
            <form action="{{ route('coordinador.professional_practices.index') }}" method="GET" class="filter-form">
                <div class="form-group">
                    <label for="career">Seleccionar Carrera:</label>
                    <select class="form-control" id="career" name="career" onchange="this.form.submit()">
                        <option value="">Todas las carreras</option>
                        @foreach($careersForSelect as $careerKey => $careerName)
                            <option value="{{ $careerKey }}" {{ $selectedCareer == $careerKey ? 'selected' : '' }}>
                                {{ $careerName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Campo de búsqueda -->
    <div class="search-section">
        <div class="search-card">
            <div class="search-header">
                <i class="fas fa-search"></i>
                <h3>Buscar Estudiante</h3>
            </div>
            <form action="{{ route('coordinador.professional_practices.index') }}" method="GET" class="search-form" id="searchForm">
                @if($selectedCareer)
                    <input type="hidden" name="career" value="{{ $selectedCareer }}">
                @endif
                <div class="search-group">
                    <input type="text" 
                           name="search" 
                           value="{{ $searchTerm }}" 
                           placeholder="Buscar por nombre o código de estudiante..."
                           class="search-input"
                           id="searchInput">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($searchTerm)
                        <a href="{{ route('coordinador.professional_practices.index', ['career' => $selectedCareer]) }}" 
                           class="clear-search-btn" title="Limpiar búsqueda">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados de búsqueda -->
    @if($searchTerm && !$selectedCareer)
        <div class="search-results-section">
            <div class="search-results-header">
                <h3>
                    <i class="fas fa-search"></i>
                    Resultados de búsqueda para: "{{ $searchTerm }}"
                </h3>
                <p>Se encontraron estudiantes en las siguientes carreras:</p>
            </div>
            
            @if($studentsWithDocuments->isEmpty())
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>No se encontraron resultados</h4>
                    <p>No hay estudiantes que coincidan con "{{ $searchTerm }}"</p>
                </div>
            @else
                <div class="search-results-grid">
                    @foreach($studentsWithDocuments as $careerData)
                        <div class="search-result-card">
                            <div class="result-card-header">
                                <div class="career-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="career-info">
                                    <h4>{{ $careerData['career_title'] }}</h4>
                                    <div class="result-stats">
                                        <span class="stat">
                                            <i class="fas fa-users"></i>
                                            {{ $careerData['total_students'] }} estudiantes encontrados
                                        </span>
                                        <span class="stat">
                                            <i class="fas fa-file-alt"></i>
                                            {{ $careerData['total_documents'] }} documentos
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="result-card-actions">
                                <a href="{{ route('coordinador.professional_practices.index', ['career' => $careerData['career'], 'search' => $searchTerm]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Ver Resultados
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Contenido Principal -->
    @if($selectedCareer)
        <!-- Vista de Carrera Específica -->
        <div class="career-details-section">
            <div class="career-header">
                <h2>
                    <i class="fas fa-graduation-cap"></i>
                    {{ $careersForSelect[$selectedCareer] ?? $selectedCareer }}
                </h2>
                <div class="career-stats">
                    <span class="stat-item">
                        <i class="fas fa-users"></i>
                        {{ $studentsWithDocuments->count() }} estudiantes
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-file-alt"></i>
                        {{ $studentsWithDocuments->sum(function($item) { return $item['documents']->count(); }) }} documentos
                    </span>
                </div>
            </div>

            @if($studentsWithDocuments->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>No hay estudiantes en esta carrera</h3>
                    <p>No hay estudiantes registrados en {{ $careersForSelect[$selectedCareer] ?? $selectedCareer }} con documentos de prácticas.</p>
                </div>
            @else
                <!-- Lista de Estudiantes con sus Documentos -->
                @foreach($studentsWithDocuments as $studentData)
                    @php
                        $student = $studentData['student'];
                        $documents = $studentData['documents'];
                    @endphp
                    
                    <div class="student-documents-card">
                        <div class="student-header">
                            <div class="student-info">
                                <h3>
                                    <i class="fas fa-user-graduate"></i>
                                    {{ $student->name }}
                                </h3>
                                <p class="student-details">
                                    <span class="student-code">{{ $student->student_code }}</span>
                                    <span class="student-semester">{{ $student->semester }}° semestre</span>
                                </p>
                            </div>
                            <div class="student-stats">
                                <span class="documents-count">{{ $documents->count() }} documentos</span>
                            </div>
                        </div>

                        @if($documents->isEmpty())
                            <div class="no-documents-message">
                                <i class="fas fa-inbox"></i>
                                <p>Este estudiante aún no ha subido ningún documento de prácticas preprofesionales.</p>
                            </div>
                        @else
                            <div class="documents-table-container">
                                <table class="document-table">
                                    <thead>
                                        <tr>
                                            <th>Tipo de Documento</th>
                                            <th>Nombre del Archivo</th>
                                            <th>Horas Completadas</th>
                                            <th>Tutor Asignado</th>
                                            <th>Fecha de Envío</th>
                                            <th>Estado Profesor</th>
                                            <th>Comentarios Profesor</th>
                                            <th>Estado Coordinador</th>
                                            <th>Comentarios Coordinador</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documents as $document)
                                            <tr>
                                                <td title="{{ $document->document_type }}">
                                                    @switch($document->document_type)
                                                        @case('convenio') Convenio de Prácticas @break
                                                        @case('plan_trabajo') Plan de Trabajo @break
                                                        @case('informe_final') Informe Final @break
                                                        @case('certificado_horas') Certificado de Horas @break
                                                        @case('otro') Otro Documento @break
                                                        @default {{ $document->document_type }}
                                                    @endswitch
                                                </td>
                                                <td>{{ $document->file_name }}</td>
                                                <td>
                                                    @if($document->document_type === 'certificado_horas' && $document->hours_completed)
                                                        <div class="hours-display">
                                                            <span class="hours-number">{{ $document->hours_completed }}</span>
                                                            <span class="hours-label">horas</span>
                                                            @php
                                                                $horasRequeridas = $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0);
                                                                $porcentaje = $horasRequeridas > 0 ? round(($document->hours_completed / $horasRequeridas) * 100, 1) : 0;
                                                            @endphp
                                                            <div class="hours-progress">
                                                                <div class="progress-bar-mini" style="width: {{ min($porcentaje, 100) }}%; background-color: {{ $porcentaje >= 100 ? '#28a745' : ($porcentaje >= 75 ? '#ffc107' : '#dc3545') }};"></div>
                                                            </div>
                                                            <small class="hours-percentage">{{ $porcentaje }}%</small>
                                                        </div>
                                                    @elseif($document->document_type === 'certificado_horas')
                                                        <span class="no-hours">Sin horas registradas</span>
                                                    @else
                                                        <span class="not-applicable">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($document->tutor)
                                                        <span class="tutor-name">{{ $document->tutor->name }}</span>
                                                        <small class="tutor-code">{{ $document->tutor->teacher_code }}</small>
                                                                                                    @else
                                                    <span class="no-tutor">
                                                        <a href="{{ route('coordinador.student_tutor_assignment.index') }}" title="Gestionar asignación de tutores">
                                                            <i class="fas fa-exclamation-triangle"></i> Sin tutor asignado
                                                        </a>
                                                    </span>
                                                @endif
                                                </td>
                                                <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="status-{{ $document->teacher_status }}">
                                                        @switch($document->teacher_status)
                                                            @case('pending') Pendiente @break
                                                            @case('approved') Aprobado @break
                                                            @case('rejected') Rechazado @break
                                                            @default {{ ucfirst($document->teacher_status) }}
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($document->teacher_comments)
                                                        <span class="comments-text">{{ $document->teacher_comments }}</span>
                                                    @else
                                                        <span class="no-comments">Sin comentarios</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="status-{{ $document->coordinator_status }}">
                                                        @switch($document->coordinator_status)
                                                            @case('pending') Pendiente @break
                                                            @case('approved') Aprobado @break
                                                            @case('rejected') Rechazado @break
                                                            @default {{ ucfirst($document->coordinator_status) }}
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($document->coordinator_comments)
                                                        <span class="comments-text">{{ $document->coordinator_comments }}</span>
                                                    @else
                                                        <span class="no-comments">Sin comentarios</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('coordinador.professional_practices.download', $document->id) }}" 
                                                           class="btn btn-sm btn-primary" title="Descargar">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-secondary" 
                                                                onclick="openStatusModal({{ $document->id }}, '{{ $document->coordinator_status }}', '{{ $document->coordinator_comments }}')" 
                                                                title="Cambiar Estado">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @else
        <!-- Vista General de Todas las Carreras -->
        <div class="careers-overview-section">
            <div class="overview-header">
                <h2>
                    <i class="fas fa-graduation-cap"></i>
                    Resumen por Carreras
                </h2>
                <p>Selecciona una carrera para ver los documentos de los estudiantes</p>
            </div>

            <div class="careers-grid">
                @foreach($studentsWithDocuments as $careerData)
                    <div class="career-card">
                        <div class="career-card-header">
                            <div class="career-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="career-info">
                                <h3>{{ $careerData['career_title'] }}</h3>
                                <div class="career-stats">
                                    <span class="stat">
                                        <i class="fas fa-users"></i>
                                        {{ $careerData['total_students'] }} estudiantes
                                    </span>
                                    <span class="stat">
                                        <i class="fas fa-file-alt"></i>
                                        {{ $careerData['total_documents'] }} documentos
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="career-card-actions">
                            <a href="{{ route('coordinador.professional_practices.index', ['career' => $careerData['career']]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Ver Documentos
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Modal para Cambiar Estado -->
<div id="statusModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-dialog" style="margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; background-color: white; border-radius: 5px;">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; margin-bottom: 20px;">
                <h5 class="modal-title">Cambiar Estado del Documento</h5>
                <button type="button" class="btn-close" onclick="closeStatusModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado:</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pendiente</option>
                            <option value="approved">Aprobado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comments" class="form-label">Comentarios (opcional):</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3" maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #dee2e6; padding-top: 15px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(documentId, currentStatus, currentComments) {
    console.log('Opening modal for document:', documentId, 'with status:', currentStatus);
    
    // Establecer el estado actual
    document.getElementById('status').value = currentStatus;
    
    // Establecer los comentarios actuales
    const commentsElement = document.getElementById('comments');
    commentsElement.value = currentComments || '';
    
    // Establecer la acción del formulario
    document.getElementById('statusForm').action = `/coordinador/professional_practices/${documentId}/update-status`;
    
    // Mostrar el modal
    document.getElementById('statusModal').style.display = 'block';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('statusModal');
    if (event.target == modal) {
        closeStatusModal();
    }
}

// Agregar listener para debugging
document.addEventListener('DOMContentLoaded', function() {
    console.log('Modal script loaded successfully');
    
    // Mejorar la funcionalidad de búsqueda
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    
    if (searchInput && searchForm) {
        // Agregar funcionalidad de búsqueda con Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
        
        // Agregar funcionalidad de búsqueda en tiempo real (opcional)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            // Solo buscar si hay al menos 2 caracteres
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    // Aquí podrías agregar búsqueda AJAX si lo deseas
                    console.log('Búsqueda sugerida:', query);
                }, 500);
            }
        });
        
        // Mejorar la experiencia visual
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    }
    
    // Agregar animaciones suaves a las tarjetas de resultados
    const resultCards = document.querySelectorAll('.search-result-card');
    resultCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection