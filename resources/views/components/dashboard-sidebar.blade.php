<aside class="dashboard-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            <span class="brand-text">ISTPET</span>
        </div>
        <button class="sidebar-toggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            @if(Auth::guard('student')->check())
                <!-- Student Navigation -->
                <li>
                    <a href="{{ route('estudiante.dashboard') }}" class="nav-link {{ request()->routeIs('estudiante.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <span>Mis Cursos</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>Tareas</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Calificaciones</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Horario</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('estudiante.notifications.index') }}" class="nav-link {{ request()->routeIs('estudiante.notifications*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Anuncios</span>
                        @php
                            $unreadCount = Auth::guard('student')->user()->unreadNotifications()->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Recursos</span>
                    </a>
                </li>
                
                @if(Auth::guard('student')->user()->semester >= 3)
                <li>
                    <a href="{{ route('estudiante.practices.index') }}" class="nav-link {{ request()->routeIs('estudiante.practices*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</span>
                    </a>
                </li>
                @else
                <li class="disabled-menu-item">
                    <a href="#" onclick="return false;" title="Disponible a partir del 3° semestre">
                        <i class="fas fa-lock"></i>
                        <span>Prácticas Preprofesionales</span>
                        <small class="semester-notice">(3°+ semestre)</small>
                    </a>
                </li>
                @endif
                
                <li>
                    <a href="{{ route('estudiante.settings') }}" class="nav-link {{ request()->routeIs('estudiante.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
                
            @elseif(Auth::guard('admin')->check())
                <!-- Admin Navigation -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Gestionar Usuarios</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Gestionar Cursos</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.statistics.index') }}" class="nav-link {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Estadísticas</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Configuración del Sistema</span>
                    </a>
                </li>
                
            @elseif(Auth::guard('coordinador')->check())
                <!-- Coordinator Navigation -->
                <li>
                    <a href="{{ route('coordinador.dashboard') }}" class="nav-link {{ request()->routeIs('coordinador.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Supervisar Docentes</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-graduate"></i>
                        <span>Supervisar Estudiantes</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Programas Académicos</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('coordinador.professional_practices.index') }}" class="nav-link {{ request()->routeIs('coordinador.professional_practices*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Prácticas Preprofesionales</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="nav-link {{ request()->routeIs('coordinador.student_tutor_assignment*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Asignación de Tutores</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('coordinador.settings') }}" class="nav-link {{ request()->routeIs('coordinador.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
                
            @elseif(Auth::guard('teacher')->check())
                <!-- Teacher Navigation -->
                <li>
                    <a href="{{ route('profesor.dashboard') }}" class="nav-link {{ request()->routeIs('profesor.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <span>Mis Clases</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>Asignar Tareas</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Calificar</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Mi Horario</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span>Publicar Anuncios</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Recursos Docentes</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('profesor.professional_practices.index') }}" class="nav-link {{ request()->routeIs('profesor.professional_practices*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Prácticas Preprofesionales</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('profesor.settings') }}" class="nav-link {{ request()->routeIs('profesor.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div class="user-info">
            @php
                $userName = 'Usuario';
                $userRole = 'Usuario';
                
                if (Auth::guard('student')->check()) {
                    $user = Auth::guard('student')->user();
                    $userName = $user->name;
                    $userRole = 'Estudiante';
                } elseif (Auth::guard('teacher')->check()) {
                    $user = Auth::guard('teacher')->user();
                    $userName = $user->name;
                    $userRole = 'Profesor';
                } elseif (Auth::guard('coordinador')->check()) {
                    $user = Auth::guard('coordinador')->user();
                    $userName = $user->name;
                    $userRole = 'Coordinador';
                } elseif (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    $userName = $user->name;
                    $userRole = 'Administrador';
                }
            @endphp
            
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <span class="user-name">{{ $userName }}</span>
                <span class="user-role">{{ $userRole }}</span>
            </div>
        </div>
        
        @php
            $logoutRoute = '#';
            if (Auth::guard('student')->check()) {
                $logoutRoute = route('estudiante.logout');
            } elseif (Auth::guard('teacher')->check()) {
                $logoutRoute = route('profesor.logout');
            } elseif (Auth::guard('coordinador')->check()) {
                $logoutRoute = route('coordinador.logout');
            } elseif (Auth::guard('admin')->check()) {
                $logoutRoute = route('admin.logout');
            }
        @endphp
        
        <form id="sidebar-logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
            @csrf
        </form>
        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
        </button>
    </div>
</aside>
