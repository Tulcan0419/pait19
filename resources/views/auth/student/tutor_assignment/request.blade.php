@extends('layouts.student-dashboard')

@section('title', 'Solicitar Asignación de Tutor - Estudiante')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student/practices.css') }}">

<div class="practices-container">
    <div class="practices-header">
        <h1><i class="fas fa-user-tie"></i> Solicitar Asignación de Tutor</h1>
        <p>Aquí puedes solicitar la asignación de un profesor como tutor para tus {{ $student->semester >= 4 ? "prácticas profesionales" : "prácticas preprofesionales" }}.</p>
    </div>

    <div class="practice-card">
        <div class="tutor-request-info">
            <div class="info-section">
                <h3><i class="fas fa-info-circle"></i> Información Importante</h3>
                <div class="info-content">
                    <p><strong>¿Por qué necesitas un tutor?</strong></p>
                    <p>El tutor es un profesor asignado por el coordinador que te guiará durante tus {{ $student->semester >= 4 ? "prácticas profesionales" : "prácticas preprofesionales" }}. Su función incluye:</p>
                    <ul>
                        <li>Revisar y aprobar los documentos que subas</li>
                        <li>Proporcionar retroalimentación sobre tu trabajo</li>
                        <li>Guiarte en el cumplimiento de los requisitos académicos</li>
                        <li>Evaluar tu progreso en las prácticas</li>
                    </ul>
                </div>
            </div>

            <div class="student-info-section">
                <h3><i class="fas fa-user-graduate"></i> Tu Información</h3>
                <div class="student-details">
                    <div class="detail-item">
                        <span class="label">Nombre:</span>
                        <span class="value">{{ $student->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Carrera:</span>
                        <span class="value">
                            @switch($student->career)
                                @case('mechanical')
                                    Mecánica
                                    @break
                                @case('software')
                                    Desarrollo de Software
                                    @break
                                @case('education')
                                    Educación Básica
                                    @break
                                @default
                                    {{ ucfirst($student->career) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Semestre:</span>
                        <span class="value">{{ $student->semester }}° semestre</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Tipo de Prácticas:</span>
                        <span class="value">{{ $student->semester >= 4 ? "Prácticas Profesionales" : "Prácticas Preprofesionales" }}</span>
                    </div>
                </div>
            </div>

            <div class="request-section">
                <h3><i class="fas fa-paper-plane"></i> Solicitud de Asignación</h3>
                <div class="request-content">
                    <p>Para solicitar la asignación de un tutor, por favor:</p>
                    <ol>
                        <li><strong>Contacta al coordinador de tu carrera</strong> directamente en la oficina de coordinación</li>
                        <li><strong>Proporciona tu información académica</strong> (código de estudiante, carrera, semestre)</li>
                        <li><strong>Explica el motivo</strong> de tu solicitud de prácticas</li>
                        <li><strong>Espera la asignación</strong> que será procesada por el coordinador</li>
                    </ol>
                    
                    <div class="contact-info">
                        <h4><i class="fas fa-phone"></i> Información de Contacto</h4>
                        <p><strong>Coordinación Académica:</strong></p>
                        <ul>
                            <li><i class="fas fa-map-marker-alt"></i> Oficina: Edificio Principal, Primer Piso</li>
                            <li><i class="fas fa-clock"></i> Horario: Lunes a Viernes, 8:00 AM - 5:00 PM</li>
                            <li><i class="fas fa-envelope"></i> Email: coordinacion@instituto.edu.ec</li>
                            <li><i class="fas fa-phone"></i> Teléfono: (02) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('estudiante.practices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Prácticas
                </a>
                <a href="{{ route('estudiante.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Ir al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.tutor-request-info {
    max-width: 800px;
    margin: 0 auto;
}

.info-section, .student-info-section, .request-section {
    margin-bottom: 30px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

.info-section h3, .student-info-section h3, .request-section h3 {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-content p, .request-content p {
    color: #424242;
    line-height: 1.6;
    margin-bottom: 15px;
}

.info-content ul, .request-content ol {
    margin-left: 20px;
    margin-bottom: 15px;
}

.info-content li, .request-content li {
    color: #424242;
    line-height: 1.6;
    margin-bottom: 8px;
}

.student-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.detail-item .label {
    font-weight: 600;
    color: var(--primary-color);
}

.detail-item .value {
    color: #424242;
    font-weight: 500;
}

.contact-info {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    margin-top: 20px;
}

.contact-info h4 {
    color: var(--primary-color);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.contact-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contact-info li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #424242;
}

.contact-info li i {
    color: var(--primary-color);
    width: 16px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .student-details {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>
@endsection
