@extends('layouts.student-dashboard')

@section('title', '{{ $student->semester >= 4 ? "Prácticas Profesionales" : "Prácticas Preprofesionales" }} - Estudiante')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student/practices.css') }}">

            <!-- Sección de Prácticas -->
            <div class="practices-container">
                
                @if(isset($access_restricted) && $access_restricted)
                    <!-- Mensaje de Acceso Restringido -->
                    <div class="access-restricted-message">
                        <div class="restricted-content">
                            <div class="restricted-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h2>No puedes acceder todavía a este sistema</h2>
                            <p class="restricted-description">
                                El gestor de documentos de prácticas está disponible únicamente para estudiantes de <strong>3° y 4° semestre</strong>.
                            </p>
                            <div class="current-semester-info">
                                <div class="semester-badge">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Tu semestre actual: <strong>{{ $student->semester }}° semestre</strong></span>
                                </div>
                            </div>
                            <div class="access-info">
                                <h3>¿Cuándo podrás acceder?</h3>
                                <ul class="access-list">
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <strong>3° Semestre:</strong> Acceso a Prácticas Preprofesionales (96 horas)
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <strong>4° Semestre:</strong> Acceso a Prácticas Profesionales (146 horas)
                                    </li>
                                </ul>
                            </div>
                            <div class="contact-info">
                                <p>
                                    <i class="fas fa-info-circle"></i>
                                    Si crees que esto es un error, contacta al coordinador académico.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                <div class="practices-header">
                    <h1><i class="fas fa-briefcase"></i>Gestión de {{ $student->semester >= 4 ? "Prácticas Profesionales" : "Prácticas Preprofesionales" }}</h1>
                    <p>Aquí puedes subir los documentos requeridos para tus {{ $student->semester >= 4 ? "prácticas profesionales" : "prácticas preprofesionales" }}, revisar el estado de tus envíos, ver tu tutor asignado y descargar plantillas.</p>
                </div>

                <!-- Información del Tutor -->
                <div class="tutor-info-card">
                    <h3><i class="fas fa-user-tie"></i> Información de tu Tutor</h3>
                    <p>El coordinador asignará un profesor como tu tutor para revisar y guiar tus {{ $student->semester >= 4 ? "prácticas profesionales" : "prácticas preprofesionales" }}. Esta información aparecerá en la tabla de documentos una vez que se asigne un tutor.</p>
                </div>

                <!-- Información de Horas Requeridas -->
                <div class="hours-info-card">
                    <h3><i class="fas fa-clock"></i> Horas Requeridas</h3>
                    <div class="hours-requirement">
                                       <div class="hours-badge">
                   <i class="fas fa-hourglass-half"></i>
                   <span class="hours-number">{{ $student->semester == 3 ? '96' : ($student->semester >= 4 ? '146' : '0') }}</span>
                   <span class="hours-text">horas obligatorias</span>
               </div>
               <div class="hours-description">
                   @if($student->semester < 3)
                       <p><strong>Prácticas Preprofesionales:</strong> 
                       Las prácticas están disponibles a partir del tercer semestre. Tu semestre actual es: <strong>{{ $student->semester }}° semestre</strong>.</p>
                   @else
                       <p><strong>{{ $student->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}:</strong> 
                       Debes completar <strong>{{ $student->semester == 3 ? '96' : '146' }} horas</strong> de {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }} para cumplir con los requisitos académicos.</p>
                   @endif
                            
                                               @php
                       $certificadoHoras = $uploadedDocuments->where('document_type', 'certificado_horas')->first();
                       $horasCompletadas = $certificadoHoras ? ($certificadoHoras->hours_completed ?? 0) : 0;
                       $horasRequeridas = $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0);
                       $porcentajeCompletado = $horasRequeridas > 0 ? round(($horasCompletadas / $horasRequeridas) * 100, 1) : 0;
                   @endphp
                            
                            @if($student->semester >= 3)
                                <div class="progress-section">
                                    <div class="progress-info">
                                        <span class="progress-text">Progreso: {{ $horasCompletadas }}/{{ $horasRequeridas }} horas</span>
                                        <span class="progress-percentage">{{ $porcentajeCompletado }}% completado</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: {{ min($porcentajeCompletado, 100) }}%; background-color: {{ $porcentajeCompletado >= 100 ? '#28a745' : ($porcentajeCompletado >= 75 ? '#ffc107' : '#dc3545') }};"></div>
                                    </div>
                                    @if($porcentajeCompletado >= 100)
                                        <div class="completion-message">
                                            <i class="fas fa-check-circle"></i>
                                            ¡Felicitaciones! Has completado todas las horas requeridas.
                                        </div>
                                    @elseif($horasCompletadas > 0)
                                        <div class="remaining-message">
                                            <i class="fas fa-info-circle"></i>
                                            Te faltan {{ $horasRequeridas - $horasCompletadas }} horas para completar el requisito.
                                        </div>
                                    @else
                                        <div class="start-message">
                                            <i class="fas fa-play-circle"></i>
                                            Comienza subiendo tu certificado de horas para ver tu progreso.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información de Documentos Requeridos -->
                <div class="documents-progress-card">
                    <h3><i class="fas fa-file-alt"></i> Progreso de Documentos</h3>
                    <div class="documents-progress">
                        @php
                            // Definir los tipos de documentos requeridos
                            $requiredDocuments = [
                                'convenio' => 'Convenio de Prácticas',
                                'plan_trabajo' => 'Plan de Trabajo',
                                'bitacora_asistencia' => 'Bitácora de Asistencia',
                                'control_avance' => 'Control de Avance',
                                'informe_estudiante' => 'Informe del Estudiante',
                                'certificado_horas' => 'Certificado de Horas'
                            ];
                            
                            // Contar documentos subidos por tipo
                            $uploadedByType = $uploadedDocuments->groupBy('document_type');
                            
                            // Contar documentos aprobados por tipo
                            $approvedByType = $uploadedDocuments->where('teacher_status', 'approved')
                                ->where('coordinator_status', 'approved')
                                ->groupBy('document_type');
                            
                            $totalRequired = count($requiredDocuments);
                            
                            // Contar solo los tipos únicos de documentos subidos (no duplicados)
                            $uploadedTypes = $uploadedDocuments->whereIn('document_type', array_keys($requiredDocuments))->pluck('document_type')->unique();
                            $totalUploaded = $uploadedTypes->count();
                            
                            // Contar solo los documentos requeridos que están aprobados
                            $totalApproved = 0;
                            foreach ($requiredDocuments as $type => $name) {
                                $document = $uploadedDocuments->where('document_type', $type)
                                    ->where('teacher_status', 'approved')
                                ->where('coordinator_status', 'approved')
                                    ->first();
                                if ($document) {
                                    $totalApproved++;
                                }
                            }
                            
                            // Contar documentos pendientes (solo tipos únicos)
                            $pendingTypes = $uploadedDocuments->whereIn('document_type', array_keys($requiredDocuments))
                                ->filter(function($doc) {
                                return $doc->teacher_status === 'pending' || $doc->coordinator_status === 'pending';
                                })->pluck('document_type')->unique();
                            $totalPending = $pendingTypes->count();
                            
                            // Contar documentos rechazados (solo tipos únicos)
                            $rejectedTypes = $uploadedDocuments->whereIn('document_type', array_keys($requiredDocuments))
                                ->filter(function($doc) {
                                return $doc->teacher_status === 'rejected' || $doc->coordinator_status === 'rejected';
                                })->pluck('document_type')->unique();
                            $totalRejected = $rejectedTypes->count();
                            
                            $documentsMissing = $totalRequired - $totalUploaded;
                            
                            // Variables para certificado
                            $hasCertificate = $uploadedDocuments->where('document_type', 'certificado_practicas')->isNotEmpty();
                            $canGenerateCertificate = $totalApproved >= $totalRequired && $porcentajeCompletado >= 100 && !$hasCertificate;
                        @endphp
                        
                        <div class="progress-summary">
                            <div class="progress-stats">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $totalRequired }}</div>
                                    <div class="stat-label">Documentos Requeridos</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $totalUploaded }}</div>
                                    <div class="stat-label">Documentos Subidos</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $totalApproved }}</div>
                                    <div class="stat-label">Documentos Aprobados</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $documentsMissing }}</div>
                                    <div class="stat-label">Documentos Faltantes</div>
                                </div>
                            </div>
                            
                            <div class="progress-bar-overall">
                                <div class="progress-info">
                                    <span class="progress-text">Progreso General: {{ $totalUploaded }}/{{ $totalRequired }} documentos</span>
                                    <span class="progress-percentage">{{ $totalRequired > 0 ? round(min(($totalUploaded / $totalRequired) * 100, 100), 1) : 0 }}% completado</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: {{ $totalRequired > 0 ? min(($totalUploaded / $totalRequired) * 100, 100) : 0 }}%; background-color: {{ $totalUploaded >= $totalRequired ? '#28a745' : ($totalUploaded >= $totalRequired * 0.5 ? '#ffc107' : '#dc3545') }};"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="documents-detail">
                            <h4>Estado por Documento:</h4>
                            <div class="documents-grid">
                                @foreach($requiredDocuments as $type => $name)
                                    @php
                                        $uploaded = $uploadedByType->get($type, collect());
                                        $approved = $approvedByType->get($type, collect());
                                        $status = 'missing';
                                        $statusText = 'Faltante';
                                        $statusIcon = 'fas fa-times-circle';
                                        $statusColor = '#dc3545';
                                        
                                        if ($uploaded->isNotEmpty()) {
                                            $latestDoc = $uploaded->first();
                                            if ($latestDoc->teacher_status === 'approved' && $latestDoc->coordinator_status === 'approved') {
                                                $status = 'approved';
                                                $statusText = 'Aprobado';
                                                $statusIcon = 'fas fa-check-circle';
                                                $statusColor = '#28a745';
                                            } elseif ($latestDoc->teacher_status === 'rejected' || $latestDoc->coordinator_status === 'rejected') {
                                                $status = 'rejected';
                                                $statusText = 'Rechazado';
                                                $statusIcon = 'fas fa-exclamation-circle';
                                                $statusColor = '#dc3545';
                                            } else {
                                                $status = 'pending';
                                                $statusText = 'Pendiente';
                                                $statusIcon = 'fas fa-clock';
                                                $statusColor = '#ffc107';
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="document-status-item status-{{ $status }}">
                                        <div class="document-icon">
                                            <i class="{{ $statusIcon }}" style="color: {{ $statusColor }};"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $name }}</div>
                                            <div class="document-status">{{ $statusText }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        

                        
                        @if($documentsMissing > 0)
                            <div class="missing-documents-alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Te faltan {{ $documentsMissing }} {{ $documentsMissing == 1 ? 'documento' : 'documentos' }} por subir.</strong>
                                <p>Completa todos los documentos requeridos para finalizar tus {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }}.</p>
                            </div>
                        @elseif($totalApproved >= $totalRequired)
                            <div class="completion-message">
                                <i class="fas fa-trophy"></i>
                                <strong>¡Felicitaciones!</strong>
                                <p>Has completado y aprobado todos los documentos requeridos para tus {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }}.</p>
                                

                                
                                @if($canGenerateCertificate)
                                    <div class="certificate-section">
                                        <div class="certificate-info">
                                            <i class="fas fa-certificate"></i>
                                            <strong>¡Puedes generar tu certificado!</strong>
                                            <p>Has cumplido con todos los requisitos. Genera tu certificado oficial de {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }}.</p>
                                        </div>
                                        <a href="{{ route('estudiante.certificate.generate') }}" class="btn-generate-certificate">
                                            <i class="fas fa-download"></i>
                                            Generar Certificado
                                        </a>
                                    </div>
                                @elseif($hasCertificate)
                                    <div class="certificate-section">
                                        <div class="certificate-info">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>Certificado generado</strong>
                                            <p>Ya tienes tu certificado de {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }}.</p>
                                        </div>
                                        @php
                                            $certificateDocument = $uploadedDocuments->where('document_type', 'certificado_practicas')->first();
                                        @endphp
                                        @if($certificateDocument)
                                            <a href="{{ route('estudiante.certificate.download', $certificateDocument->id) }}" class="btn-download-certificate">
                                                <i class="fas fa-download"></i>
                                                Descargar Certificado
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="pending-approval-message">
                                <i class="fas fa-info-circle"></i>
                                <strong>Documentos en revisión</strong>
                                <p>Todos los documentos han sido subidos. Espera la aprobación de tu tutor y coordinador.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="practice-card">
                    <h3><i class="fas fa-cloud-upload-alt"></i>Subir Nuevo Documento</h3>
                    <form action="{{ route('estudiante.practices.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                        @csrf
                        <div class="form-group">
                            <label for="document_type">Tipo de Documento:</label>
                            <select class="form-control" id="document_type" name="document_type" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="convenio">Convenio de Prácticas</option>
                                <option value="plan_trabajo">Plan de Trabajo</option>
                                <option value="bitacora_asistencia">Bitácora De Asistencia</option>
                                <option value="control_avance">Control De Avance</option>
                                <option value="informe_estudiante">Informe Del Estudiante</option>
                                <option value="certificado_horas">Certificado de Horas</option>
                                <option value="otro">Otro (Especificar)</option>
                            </select>
                        </div>
                        <!-- Campo de horas completadas (solo visible para certificado de horas) -->
                        <div class="form-group" id="hours_completed_group" style="display: none;">
                            <label for="hours_completed">Horas Completadas:</label>
                            <input type="number" class="form-control" id="hours_completed" name="hours_completed" min="0" max="1000" placeholder="Ingresa el número de horas completadas">
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i>
                                @if($student->semester >= 3)
                                    Horas requeridas: <strong>{{ $student->semester == 3 ? '96' : '146' }} horas</strong> para {{ $student->semester >= 4 ? 'prácticas profesionales' : 'prácticas preprofesionales' }}
                                @else
                                    Las prácticas están disponibles a partir del tercer semestre.
                                @endif
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_file">Seleccionar Archivo:</label>
                            <input type="file" class="form-control form-control-file" id="document_file" name="document_file" required>
                            <small class="form-text">Formatos permitidos: PDF, DOCX. Tamaño máximo: 5MB.</small>
                        </div>
                        <div class="form-group">
                            <label for="comments">Comentarios (Opcional):</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Añade cualquier comentario relevante sobre el documento."></textarea>
                        </div>
                        <button type="submit" class="btn-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Subir Documento
                        </button>
                    </form>
                </div>

                <div class="practice-card">
                    <h3><i class="fas fa-file-alt"></i>Documentos Enviados</h3>
                    @if($uploadedDocuments->isEmpty())
                        <p class="no-documents-message">Aún no has subido ningún documento de prácticas.</p>
                    @else
                        <div class="table-responsive">
                            <table class="document-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Tipo</th>
                                        <th style="width: 18%;">Nombre del Archivo</th>
                                        <th style="width: 15%;">Tutor Asignado</th>
                                        <th style="width: 10%;">Fecha de Envío</th>
                                        <th style="width: 10%;">Estado Profesor</th>
                                        <th style="width: 10%;">Estado Coordinador</th>
                                        <th style="width: 17%;">Observaciones</th>
                                        <th style="width: 10%;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($uploadedDocuments as $document)
                                        <tr>
                                            <td title="{{ $document->document_type }}">
                                                @switch($document->document_type)
                                                    @case('convenio')
                                                        Convenio de Prácticas
                                                        @break
                                                    @case('plan_trabajo')
                                                        Plan de Trabajo
                                                        @break
                                                    @case('bitacora_asistencia')
                                                        Bitácora De Asistencia
                                                        @break
                                                    @case('control_avance')
                                                        Control De Avance
                                                        @break
                                                    @case('informe_estudiante')
                                                        Informe Del Estudiante
                                                        @break
                                                    @case('certificado_horas')
                                                        Certificado de Horas
                                                        @break
                                                    @case('otro')
                                                        Otro Documento
                                                        @break
                                                    @default
                                                        {{ $document->document_type }}
                                                @endswitch
                                                @if($document->document_type === 'certificado_horas' && $document->hours_completed)
                                                    <br><small class="hours-info">
                                                        <i class="fas fa-clock"></i>
                                                        {{ $document->hours_completed }} horas
                                                    </small>
                                                @endif
                                            </td>
                                            <td title="{{ $document->file_name }}">{{ $document->file_name }}</td>
                                            <td>
                                                @if($document->activeTutor)
                                                    <div class="tutor-info">
                                                        <strong>{{ $document->activeTutor->name }}</strong>
                                                        <small>{{ $document->activeTutor->teacher_code }}</small>
                                                    </div>
                                                @else
                                                    <span class="no-tutor">
                                                        <a href="{{ route('estudiante.tutor-assignment.request') }}" title="Solicitar asignación de tutor">
                                                            <i class="fas fa-exclamation-triangle"></i> Sin tutor asignado
                                                        </a>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $document->created_at->format('Y-m-d') }}</td>
                                            <td><span class="status-{{ $document->teacher_status }}">
                                                @switch($document->teacher_status)
                                                    @case('pending')
                                                        Pendiente
                                                        @break
                                                    @case('approved')
                                                        Aprobado
                                                        @break
                                                    @case('rejected')
                                                        Rechazado
                                                        @break
                                                    @default
                                                        {{ ucfirst($document->teacher_status) }}
                                                @endswitch
                                            </span></td>
                                            <td><span class="status-{{ $document->coordinator_status }}">
                                                @switch($document->coordinator_status)
                                                    @case('pending')
                                                        Pendiente
                                                        @break
                                                    @case('approved')
                                                        Aprobado
                                                        @break
                                                    @case('rejected')
                                                        Rechazado
                                                        @break
                                                    @default
                                                        {{ ucfirst($document->coordinator_status) }}
                                                @endswitch
                                            </span></td>
                                            <td>
                                                @if($document->teacher_comments)
                                                    <div><strong>Profesor:</strong> <span @if($document->teacher_status == 'rejected') style="color:#dc3545;font-weight:bold;" @endif>{{ $document->teacher_comments }}</span></div>
                                                @endif
                                                @if($document->coordinator_comments)
                                                    <div><strong>Coordinador:</strong> <span @if($document->coordinator_status == 'rejected') style="color:#dc3545;font-weight:bold;" @endif>{{ $document->coordinator_comments }}</span></div>
                                                @endif
                                                @if(!$document->teacher_comments && !$document->coordinator_comments)
                                                    <span class="text-muted">Sin observaciones</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="action-btn view-btn" title="Ver"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('estudiante.practices.download', $document->id) }}" class="action-btn download-btn" title="Descargar"><i class="fas fa-download"></i></a>
                                                @if(($document->teacher_status == 'pending' || $document->teacher_status == 'rejected') && 
                                                     ($document->coordinator_status == 'pending' || $document->coordinator_status == 'rejected'))
                                                    <a href="#" class="action-btn delete-btn" title="Eliminar" onclick="confirmDelete({{ $document->id }})"><i class="fas fa-trash-alt"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="templates-section">
                    <h3><i class="fas fa-file-download"></i>Plantillas y Guías</h3>
                    <p>Descarga las plantillas oficiales y guías para la elaboración de tus documentos de prácticas.</p>
                    <ul class="templates-list">
                        <li>
                            <a href="{{ asset('plantillas/Bitácora de Asistencia.docx') }}" download>
                                <i class="fas fa-file-word"></i> Bitácora de Asistencia (DOCX)
                            </a>
                        </li>
                        <li>
                            <a href="{{ asset('plantillas/Control de Avance.docx') }}" download>
                                <i class="fas fa-file-word"></i> Control de Avance (DOCX)
                            </a>
                        </li>
                        <li>
                            <a href="{{ asset('plantillas/Informe del Estudiante (Final).docx') }}" download>
                                <i class="fas fa-file-word"></i> Informe del Estudiante (Final) (DOCX)
                            </a>
                        </li>
                        <li>
                            <a href="{{ asset('plantillas/Plan de Aprendizaje.docx') }}" download>
                                <i class="fas fa-file-word"></i> Plan de Aprendizaje (DOCX)
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </div>

@endsection

@section('scripts')
<script>
    // Función para confirmar eliminación
    function confirmDelete(documentId) {
        if (confirm('¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.')) {
            // Crear un formulario temporal para enviar la solicitud DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("estudiante.practices.destroy", ":id") }}'.replace(':id', documentId);
            
            // Agregar el token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Agregar el método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            // Agregar el formulario al DOM y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<script>
    // Script para mostrar/ocultar el campo de horas completadas
    document.addEventListener('DOMContentLoaded', function() {
        const documentTypeSelect = document.getElementById('document_type');
        const hoursCompletedGroup = document.getElementById('hours_completed_group');
        const hoursCompletedInput = document.getElementById('hours_completed');
        
        if (documentTypeSelect && hoursCompletedGroup) {
            documentTypeSelect.addEventListener('change', function() {
                if (this.value === 'certificado_horas') {
                    hoursCompletedGroup.style.display = 'block';
                    hoursCompletedInput.required = true;
                } else {
                    hoursCompletedGroup.style.display = 'none';
                    hoursCompletedInput.required = false;
                    hoursCompletedInput.value = '';
                }
            });
        }
        
        // Validación adicional para el campo de horas
        if (hoursCompletedInput) {
            hoursCompletedInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                const maxHours = {{ $student->semester == 3 ? 96 : ($student->semester >= 4 ? 146 : 0) }};
                
                if (value > maxHours) {
                    this.setCustomValidity(`Las horas no pueden exceder ${maxHours} horas`);
                } else if (value < 0) {
                    this.setCustomValidity('Las horas no pueden ser negativas');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    });
</script>
                @endif
@endsection