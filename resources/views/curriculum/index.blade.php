@extends('layouts.student-dashboard')

@section('title', 'Malla Curricular - Desarrollo de Software - ISTPET')

@section('content')
    <!-- Header de la Malla Curricular -->
    <div class="dashboard-grid">
        <div class="card-item">
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <h3>Malla Curricular</h3>
            <p>Tecnólogo/a Superior en Desarrollo de Software - {{ $stats['total_subjects'] }} materias distribuidas en 4 semestres</p>
            <a href="#curriculum-content" class="btn-view">Ver Malla Completa</a>
        </div>
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-calculator"></i></div>
            <h3>Créditos Totales</h3>
            <p>{{ $stats['total_credits'] }} créditos académicos para completar la carrera</p>
            <a href="#curriculum-content" class="btn-view">Ver Detalles</a>
        </div>
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <h3>Duración</h3>
            <p>2 años académicos (4 semestres) de formación integral</p>
            <a href="#curriculum-content" class="btn-view">Ver Semestres</a>
        </div>
        
        @if($student)
        <div class="card-item">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h3>Mi Progreso</h3>
            <p>Consulta tu avance académico y materias del semestre actual</p>
            <a href="{{ route('estudiante.curriculum.my-progress') }}" class="btn-view">Ver Progreso</a>
        </div>
        @endif
    </div>

    <!-- Leyenda de Unidades Curriculares -->
    <div class="recent-activities">
        <h2><i class="fas fa-palette"></i> Unidades de Organización Curricular</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-book" style="color: #87CEEB;"></i>
                </div>
                <div class="activity-content">
                    <h4>Unidad Básica</h4>
                    <p>{{ $stats['by_unit']['basica']['count'] }} materias - {{ $stats['by_unit']['basica']['credits'] }} créditos</p>
                    <span class="activity-time">Fundamentos teóricos</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-laptop-code" style="color: #FFA500;"></i>
                </div>
                <div class="activity-content">
                    <h4>Unidad Profesional</h4>
                    <p>{{ $stats['by_unit']['profesional']['count'] }} materias - {{ $stats['by_unit']['profesional']['credits'] }} créditos</p>
                    <span class="activity-time">Formación técnica especializada</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-project-diagram" style="color: #90EE90;"></i>
                </div>
                <div class="activity-content">
                    <h4>Integración Curricular</h4>
                    <p>{{ $stats['by_unit']['integracion']['count'] }} materias - {{ $stats['by_unit']['integracion']['credits'] }} créditos</p>
                    <span class="activity-time">Proyectos integradores</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Malla Curricular por Semestres -->
    <div id="curriculum-content">
        @foreach($subjects as $semester => $semesterSubjects)
        <div class="recent-activities">
            <h2>
                <i class="fas fa-calendar-alt"></i> 
                {{ $semester == 1 ? 'Primer' : ($semester == 2 ? 'Segundo' : ($semester == 3 ? 'Tercer' : 'Cuarto')) }} Semestre
                @if($student && $student->semester == $semester)
                    <span class="badge" style="background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; margin-left: 10px;">Tu semestre actual</span>
                @endif
            </h2>
            <div class="activity-list">
                @foreach($semesterSubjects->groupBy('curricular_unit') as $unit => $unitSubjects)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-{{ $unit == 'basica' ? 'book' : ($unit == 'profesional' ? 'laptop-code' : 'project-diagram') }}" 
                           style="color: {{ $unit == 'basica' ? '#87CEEB' : ($unit == 'profesional' ? '#FFA500' : '#90EE90') }};"></i>
                    </div>
                    <div class="activity-content">
                        <h4>{{ ucfirst($unit) }}</h4>
                        <div class="subjects-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 10px;">
                            @foreach($unitSubjects as $subject)
                            <div class="card-item" style="padding: 15px; margin: 0; {{ $student && $student->semester == $semester ? 'border: 2px solid var(--primary-color); background: #f8fbff;' : '' }}">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                    <h5 style="margin: 0; font-size: 0.9rem; color: var(--text-dark);">{{ $subject->name }}</h5>
                                    <span style="background: var(--primary-color); color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem;">{{ $subject->credits }} créditos</span>
                                </div>
                                <p style="font-size: 0.8rem; color: var(--text-light); margin-bottom: 8px;">{{ $subject->description }}</p>
                                
                                @if($student && isset($studentProgress[$subject->id]))
                                    @php $progress = $studentProgress[$subject->id]; @endphp
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                        @if($progress['is_enrolled'])
                                            <span style="background: #d4edda; color: #155724; padding: 2px 6px; border-radius: 8px; font-size: 0.7rem;">Inscrito</span>
                                        @endif
                                        @if($progress['is_completed'])
                                            <span style="background: #cce5ff; color: #004085; padding: 2px 6px; border-radius: 8px; font-size: 0.7rem;">Aprobado</span>
                                        @elseif($progress['average_grade'])
                                            <span style="background: #fff3cd; color: #856404; padding: 2px 6px; border-radius: 8px; font-size: 0.7rem;">En curso ({{ number_format($progress['average_grade'], 1) }})</span>
                                        @else
                                            <span style="background: #f8d7da; color: #721c24; padding: 2px 6px; border-radius: 8px; font-size: 0.7rem;">Pendiente</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <span class="activity-time">{{ $unitSubjects->count() }} materias - {{ $unitSubjects->sum('credits') }} créditos</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Resumen Final -->
    <div class="recent-activities">
        <h2><i class="fas fa-chart-pie"></i> Resumen de la Carrera</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="activity-content">
                    <h4>Total de Materias</h4>
                    <p>{{ $stats['total_subjects'] }} materias distribuidas en 4 semestres</p>
                    <span class="activity-time">{{ $stats['total_credits'] }} créditos académicos</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="activity-content">
                    <h4>Duración de la Carrera</h4>
                    <p>2 años académicos con formación integral en desarrollo de software</p>
                    <span class="activity-time">Enfoque en tecnologías modernas</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="activity-content">
                    <h4>Título a Obtener</h4>
                    <p>Tecnólogo/a Superior en Desarrollo de Software</p>
                    <span class="activity-time">Reconocido por el SENESCYT</span>
                </div>
            </div>
        </div>
    </div>
@endsection

 