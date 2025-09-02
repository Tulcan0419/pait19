@extends('layouts.student-dashboard')

@section('title', 'Mi Progreso Académico - ISTPET')

@section('content')
    <!-- Resumen del Progreso -->
    <div class="dashboard-grid">
        @php
            $totalSubjects = count($progress);
            $completedSubjects = collect($progress)->where('is_completed', true)->count();
            $enrolledSubjects = collect($progress)->where('is_enrolled', true)->count();
            $pendingSubjects = $totalSubjects - $enrolledSubjects;
            $completionPercentage = $totalSubjects > 0 ? round(($completedSubjects / $totalSubjects) * 100, 1) : 0;
        @endphp
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-book"></i></div>
            <h3>Materias Totales</h3>
            <p>{{ $totalSubjects }} materias en la carrera</p>
            <a href="#progress-details" class="btn-view">Ver Detalles</a>
        </div>
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <h3>Materias Aprobadas</h3>
            <p>{{ $completedSubjects }} materias completadas exitosamente</p>
            <a href="#progress-details" class="btn-view">Ver Progreso</a>
        </div>
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <h3>Materias Inscritas</h3>
            <p>{{ $enrolledSubjects }} materias en las que estás inscrito</p>
            <a href="#progress-details" class="btn-view">Ver Estado</a>
        </div>
        
        <div class="card-item">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <h3>Materias Pendientes</h3>
            <p>{{ $pendingSubjects }} materias por cursar</p>
            <a href="#progress-details" class="btn-view">Ver Planificación</a>
        </div>
    </div>

    <!-- Información del Estudiante -->
    <div class="recent-activities">
        <h2><i class="fas fa-user"></i> Información del Estudiante</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="activity-content">
                    <h4>{{ $student->name }}</h4>
                    <p>Carrera: {{ ucfirst($student->career) }}</p>
                    <span class="activity-time">Semestre {{ $student->semester }}°</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="activity-content">
                    <h4>Progreso General</h4>
                    <p>{{ $completionPercentage }}% de la carrera completada</p>
                    <span class="activity-time">{{ $completedSubjects }} de {{ $totalSubjects }} materias aprobadas</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="activity-content">
                    <h4>Promedio General</h4>
                    @php
                        $allGrades = collect($progress)->pluck('average_grade')->filter();
                        $generalAverage = $allGrades->count() > 0 ? $allGrades->avg() : 0;
                    @endphp
                    <p>{{ number_format($generalAverage, 1) }}/10 - Excelente rendimiento académico</p>
                    <span class="activity-time">Basado en todas las materias cursadas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Progreso por Semestres -->
    <div id="progress-details">
        @foreach($subjects as $semester => $semesterSubjects)
        @php
            $semesterProgress = collect($progress)->filter(function($item) use ($semester) {
                return $item['subject']->semester == $semester;
            });
            $semesterCompleted = $semesterProgress->where('is_completed', true)->count();
            $semesterTotal = $semesterProgress->count();
            $semesterPercentage = $semesterTotal > 0 ? round(($semesterCompleted / $semesterTotal) * 100, 1) : 0;
        @endphp
        <div class="recent-activities">
            <h2>
                <i class="fas fa-calendar-alt"></i> 
                {{ $semester == 1 ? 'Primer' : ($semester == 2 ? 'Segundo' : ($semester == 3 ? 'Tercer' : 'Cuarto')) }} Semestre
                @if($student->semester == $semester)
                    <span class="badge" style="background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; margin-left: 10px;">Tu semestre actual</span>
                @endif
                <span class="badge" style="background: #28a745; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; margin-left: 10px;">{{ $semesterPercentage }}% completado</span>
            </h2>
            <div class="activity-list">
                @foreach($semesterSubjects as $subject)
                    @php $subjectProgress = $progress[$subject->id] ?? null; @endphp
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-{{ $subject->curricular_unit == 'basica' ? 'book' : ($subject->curricular_unit == 'profesional' ? 'laptop-code' : 'project-diagram') }}" 
                               style="color: {{ $subject->curricular_unit == 'basica' ? '#87CEEB' : ($subject->curricular_unit == 'profesional' ? '#FFA500' : '#90EE90') }};"></i>
                        </div>
                        <div class="activity-content">
                            <h4>{{ $subject->name }}</h4>
                            <p>{{ $subject->description }}</p>
                            <div style="display: flex; gap: 10px; margin-top: 8px;">
                                <span style="background: var(--primary-color); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">{{ $subject->credits }} créditos</span>
                                <span style="background: {{ $subject->curricular_unit == 'basica' ? '#87CEEB' : ($subject->curricular_unit == 'profesional' ? '#FFA500' : '#90EE90') }}; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">{{ ucfirst($subject->curricular_unit) }}</span>
                                @if($subjectProgress)
                                    @if($subjectProgress['is_completed'])
                                        <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                                            <i class="fas fa-check"></i> Aprobado
                                            @if($subjectProgress['average_grade'])
                                                ({{ number_format($subjectProgress['average_grade'], 1) }})
                                            @endif
                                        </span>
                                    @elseif($subjectProgress['is_enrolled'])
                                        <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                                            <i class="fas fa-clock"></i> En curso
                                            @if($subjectProgress['average_grade'])
                                                ({{ number_format($subjectProgress['average_grade'], 1) }})
                                            @endif
                                        </span>
                                    @else
                                        <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                                            <i class="fas fa-hourglass-half"></i> Pendiente
                                        </span>
                                    @endif
                                @else
                                    <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                                        <i class="fas fa-hourglass-half"></i> No inscrito
                                    </span>
                                @endif
                            </div>
                            <span class="activity-time">{{ $semesterCompleted }}/{{ $semesterTotal }} materias completadas en este semestre</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Estadísticas Detalladas -->
    <div class="recent-activities">
        <h2><i class="fas fa-chart-bar"></i> Estadísticas Detalladas</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="activity-content">
                    <h4>Rendimiento Académico</h4>
                    <p>Promedio general de {{ number_format($generalAverage, 1) }}/10 con excelente desempeño</p>
                    <span class="activity-time">Basado en todas las materias cursadas</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-target"></i>
                </div>
                <div class="activity-content">
                    <h4>Objetivos del Semestre</h4>
                    @php
                        $inProgress = collect($progress)->filter(function($item) {
                            return $item['is_enrolled'] && !$item['is_completed'] && $item['average_grade'];
                        })->count();
                    @endphp
                    <p>{{ $inProgress }} materias en progreso actualmente</p>
                    <span class="activity-time">Mantén el excelente rendimiento</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-road"></i>
                </div>
                <div class="activity-content">
                    <h4>Camino a la Graduación</h4>
                    <p>{{ $pendingSubjects }} materias restantes para completar la carrera</p>
                    <span class="activity-time">Estás en el {{ round(($completedSubjects / $totalSubjects) * 100, 1) }}% del camino</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones del Estudiante -->
    <div class="recent-activities">
        <h2><i class="fas fa-tools"></i> Acciones Disponibles</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <div class="activity-content">
                    <h4>Volver a Malla Curricular</h4>
                    <p>Consulta la malla curricular completa</p>
                    <a href="{{ route('estudiante.curriculum.index') }}" class="btn-view" style="margin-top: 10px;">Ver Malla</a>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="activity-content">
                    <h4>Ver Calificaciones Detalladas</h4>
                    <p>Consulta tus calificaciones por materia</p>
                    <a href="{{ route('estudiante.grades.index') }}" class="btn-view" style="margin-top: 10px;">Ver Calificaciones</a>
                </div>
            </div>
        </div>
    </div>
@endsection

 