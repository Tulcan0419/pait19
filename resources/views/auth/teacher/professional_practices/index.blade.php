@extends('layouts.teacher-dashboard')

@section('title', 'Gestión de Prácticas Preprofesionales - Profesor')

@section('content')
<link rel="stylesheet" href="{{ asset('css/teacher/professional_practices.css') }}">
    <!-- Sección de Gestión de Prácticas Preprofesionales para Profesor -->
    <section id="professional-practices-teacher" class="professional-practices-section">
        <div class="section-header">
            <h2><i class="fas fa-briefcase"></i> Revisión de Prácticas Preprofesionales</h2>
            <p class="section-description">Gestiona y revisa los documentos de prácticas preprofesionales organizados por carreras. Cambia estados, añade comentarios y descarga archivos de manera eficiente.</p>
            
            <!-- Navegación rápida -->
            <div class="quick-navigation">
                <a href="{{ route('profesor.dashboard') }}" class="nav-btn">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
        </div>

        @if(!$hasAssignedStudents)
            <div class="no-access-message">
                <div class="message-content">
                    <i class="fas fa-info-circle"></i>
                    <h3>No tienes estudiantes asignados como tutor</h3>
                    <p>Actualmente no tienes acceso a la gestión de prácticas preprofesionales porque no se te han asignado estudiantes como tutor.</p>
                    <div class="message-actions">
                        <a href="{{ route('profesor.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                        <p class="contact-info">
                            Si crees que esto es un error, contacta al coordinador o administrador del sistema.
                        </p>
                    </div>
                </div>
            </div>
        @else
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Se encontraron los siguientes errores:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="career-select-card">
                <h3><i class="fas fa-graduation-cap"></i> Seleccionar Carrera</h3>
                <form action="{{ route('profesor.professional_practices.index') }}" method="GET">
                    <div class="form-group">
                        <label for="career">Carrera:</label>
                        <select class="form-control" id="career" name="career" onchange="this.form.submit()">
                            <option value="">Ver todas las carreras</option>
                            @foreach($careersForSelect as $careerKey => $careerName)
                                <option value="{{ $careerKey }}" {{ $selectedCareer == $careerKey ? 'selected' : '' }}>
                                    {{ $careerName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Campo de búsqueda -->
            <div class="search-card">
                <h3><i class="fas fa-search"></i> Buscar Estudiante</h3>
                <form action="{{ route('profesor.professional_practices.index') }}" method="GET" class="search-form" id="searchForm">
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
                            <a href="{{ route('profesor.professional_practices.index', ['career' => $selectedCareer]) }}" 
                               class="clear-search-btn" title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
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
                                        <a href="{{ route('profesor.professional_practices.index', ['career' => $careerData['career'], 'search' => $searchTerm]) }}" 
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

            @if($selectedCareer)
                <!-- Vista detallada de una carrera específica -->
                <div class="document-list-card">
                    <h3>
                        <i class="fas fa-folder-open"></i>
                        @php
                            $careerName = 'Carrera';
                            if ($selectedCareer && is_string($selectedCareer) && isset($careersForSelect[$selectedCareer])) {
                                $careerName = $careersForSelect[$selectedCareer];
                            } elseif ($selectedCareer && is_string($selectedCareer)) {
                                $careerName = $selectedCareer;
                            }
                        @endphp
                        Documentos de {{ $careerName }}
                    </h3>
                    @if($studentsWithDocuments->isEmpty())
                        <div class="no-documents-message">
                            <i class="fas fa-inbox"></i>
                            <p>No hay estudiantes con documentos en esta carrera.</p>
                        </div>
                    @else
                        @foreach($studentsWithDocuments as $studentData)
                            <div class="student-section">
                                <div class="student-header">
                                    <div class="student-avatar">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="student-details">
                                        <h4>{{ $studentData['student']->name }}</h4>
                                        <div class="student-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-id-card"></i>
                                                {{ $studentData['student']->student_code }}
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-layer-group"></i>
                                                {{ $studentData['student']->semester }}° Semestre
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-file-alt"></i>
                                                {{ $studentData['documents']->count() }} documentos
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($studentData['documents']->isEmpty())
                                    <div class="no-documents-message">
                                        <i class="fas fa-file-alt"></i>
                                        <p>Este estudiante aún no ha subido ningún documento de prácticas preprofesionales.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="document-table">
                                            <thead>
                                                <tr>
                                                    <th>Tipo de Documento</th>
                                                    <th>Nombre del Archivo</th>
                                                    <th>Horas Completadas</th>
                                                    
                                                    <th>Fecha de Envío</th>
                                                    <th>Estado Profesor</th>
                                                    <th>Comentarios Profesor</th>
                                                    <th>Estado Coordinador</th>
                                                    <th>Comentarios Coordinador</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($studentData['documents'] as $document)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $document->document_type }}</strong>
                                                        </td>
                                                        <td>
                                                            <div class="file-name">
                                                                {{ $document->file_name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($document->document_type === 'certificado_horas' && $document->hours_completed)
                                                                <div class="hours-display">
                                                                    <span class="hours-number">{{ $document->hours_completed }}</span>
                                                                    <span class="hours-label">horas</span>
                                                                    @php
                                                                        $horasRequeridas = $studentData['student']->semester == 3 ? 96 : ($studentData['student']->semester >= 4 ? 146 : 0);
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
                                                            <span class="date-display">{{ $document->created_at->format('d/m/Y') }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="status-badge status-{{ $document->teacher_status }}">
                                                                @if($document->teacher_status == 'pending')
                                                                    Pendiente
                                                                @elseif($document->teacher_status == 'approved')
                                                                    Aprobado
                                                                @elseif($document->teacher_status == 'rejected')
                                                                    Rechazado
                                                                @else
                                                                    {{ ucfirst($document->teacher_status) }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="comments-display">
                                                                {{ $document->teacher_comments ?? 'Sin comentarios' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="status-badge status-{{ $document->coordinator_status }}">
                                                                @if($document->coordinator_status == 'pending')
                                                                    Pendiente
                                                                @elseif($document->coordinator_status == 'approved')
                                                                    Aprobado
                                                                @elseif($document->coordinator_status == 'rejected')
                                                                    Rechazado
                                                                @else
                                                                    {{ ucfirst($document->coordinator_status) }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="comments-display">
                                                                {{ $document->coordinator_comments ?? 'Sin comentarios' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="action-btn view-btn" title="Ver documento">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('profesor.professional_practices.download', $document->id) }}" class="action-btn download-btn" title="Descargar documento">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                <form action="{{ route('profesor.professional_practices.update_status', $document->id) }}" method="POST" class="status-form">
                                                                    <select name="status" class="status-select">
                                                                        <option value="pending" {{ $document->teacher_status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                                        <option value="approved" {{ $document->teacher_status == 'approved' ? 'selected' : '' }}>Aprobado</option>
                                                                        <option value="rejected" {{ $document->teacher_status == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                                                    </select>
                                                                    <textarea name="comments" class="comments-input" placeholder="Comentarios del profesor (opcional)" rows="2">{{ $document->teacher_comments }}</textarea>
                                                                    <button type="submit" class="btn-update-status" title="Actualizar estado">
                                                                        <i class="fas fa-save"></i>
                                                                        Actualizar
                                                                    </button>
                                                                </form>
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
                <!-- Vista general de todas las carreras -->
                <div class="careers-overview">
                    <div class="summary-cards">
                        @foreach($studentsWithDocuments as $careerData)
                            <div class="summary-card career-card" onclick="window.location.href='{{ route('profesor.professional_practices.index', ['career' => $careerData['career']]) }}'">
                                <div class="card-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="card-content">
                                    <h3>{{ $careerData['career_title'] }}</h3>
                                    <div class="card-number">{{ $careerData['total_students'] }}</div>
                                    <div class="card-description">Estudiantes asignados</div>
                                    <div class="career-documents-info">
                                        <span class="documents-count">{{ $careerData['total_documents'] }} documentos</span>
                                    </div>
                                    <div class="career-action">
                                        <i class="fas fa-arrow-right"></i>
                                        Ver documentos
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                @if($studentsWithDocuments->isEmpty())
                    <div class="no-documents-message">
                        <i class="fas fa-graduation-cap"></i>
                        <p>No hay carreras con estudiantes registrados.</p>
                    </div>
                @endif
            @endif
        @endif
    </section>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
        
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
        
        // Mejorar la experiencia de las tarjetas de carrera
        const careerCards = document.querySelectorAll('.career-card');
        careerCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            });
        });
    });
</script>
@endpush