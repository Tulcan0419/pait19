@extends('layouts.admin-dashboard')

@section('title', 'Reportes Generales - Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">

<div class="reports-container">
    <div class="reports-header">
        <h1><i class="fas fa-chart-bar"></i>Reportes Generales</h1>
        <p>Genera reportes detallados del rendimiento académico del instituto</p>
    </div>

    <div class="reports-grid">
        <!-- Reporte de Estudiantes -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="report-content">
                <h3>Reporte de Estudiantes</h3>
                <p>Análisis detallado de la población estudiantil por carrera, semestre y rendimiento académico.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">1,247</span>
                        <span class="stat-label">Total Estudiantes</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Carreras</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Profesores -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="report-content">
                <h3>Reporte de Profesores</h3>
                <p>Evaluación del desempeño docente y carga académica por departamento.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">89</span>
                        <span class="stat-label">Total Profesores</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">12</span>
                        <span class="stat-label">Departamentos</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Cursos -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="report-content">
                <h3>Reporte de Cursos</h3>
                <p>Análisis de cursos activos, materias y distribución de carga académica.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">156</span>
                        <span class="stat-label">Cursos Activos</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">45</span>
                        <span class="stat-label">Materias</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Prácticas -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="report-content">
                <h3>Reporte de Prácticas</h3>
                <p>Estado de prácticas preprofesionales y documentos subidos por los estudiantes.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">2,341</span>
                        <span class="stat-label">Documentos</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">85%</span>
                        <span class="stat-label">Aprobados</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Rendimiento -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="report-content">
                <h3>Reporte de Rendimiento</h3>
                <p>Métricas de rendimiento académico y estadísticas de aprobación por materia.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">92%</span>
                        <span class="stat-label">Aprobación</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">4.2</span>
                        <span class="stat-label">Promedio</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Financiero -->
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="report-content">
                <h3>Reporte Financiero</h3>
                <p>Análisis de matrículas, pagos y estado financiero del instituto.</p>
                <div class="report-stats">
                    <div class="stat">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Cobranza</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">$2.5M</span>
                        <span class="stat-label">Ingresos</span>
                    </div>
                </div>
                <a href="#" class="btn-generate-report">
                    <i class="fas fa-download"></i>
                    Generar Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Sección de Reportes Personalizados -->
    <div class="custom-reports-section">
        <h2><i class="fas fa-cogs"></i>Reportes Personalizados</h2>
        <div class="custom-reports-grid">
            <div class="custom-report-form">
                <h3>Crear Reporte Personalizado</h3>
                <form>
                    <div class="form-group">
                        <label for="report-type">Tipo de Reporte:</label>
                        <select id="report-type" class="form-control">
                            <option value="">Seleccionar tipo</option>
                            <option value="students">Estudiantes</option>
                            <option value="teachers">Profesores</option>
                            <option value="courses">Cursos</option>
                            <option value="practices">Prácticas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date-range">Rango de Fechas:</label>
                        <select id="date-range" class="form-control">
                            <option value="">Seleccionar rango</option>
                            <option value="last-week">Última semana</option>
                            <option value="last-month">Último mes</option>
                            <option value="last-quarter">Último trimestre</option>
                            <option value="last-year">Último año</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="format">Formato:</label>
                        <select id="format" class="form-control">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-custom-report">
                        <i class="fas fa-magic"></i>
                        Generar Reporte Personalizado
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sección de Reportes Recientes -->
    <div class="recent-reports-section">
        <h2><i class="fas fa-history"></i>Reportes Recientes</h2>
        <div class="recent-reports-list">
            <div class="recent-report-item">
                <div class="report-info">
                    <h4>Reporte de Estudiantes - Enero 2024</h4>
                    <p>Generado el 15 de Enero, 2024</p>
                </div>
                <div class="report-actions">
                    <a href="#" class="btn-download" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn-view" title="Ver">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            <div class="recent-report-item">
                <div class="report-info">
                    <h4>Reporte de Rendimiento - Diciembre 2023</h4>
                    <p>Generado el 30 de Diciembre, 2023</p>
                </div>
                <div class="report-actions">
                    <a href="#" class="btn-download" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn-view" title="Ver">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 