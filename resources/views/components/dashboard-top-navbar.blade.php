<nav class="top-navbar">
    <div class="navbar-left">
        <div class="navbar-brand">
            <i class="fas fa-graduation-cap"></i>
            <span class="brand-text">Tecnológico Traversari - ISTPET</span>
        </div>
        
        <!-- Navigation Links -->
        <div class="navbar-nav">
            @if(Auth::guard('student')->check())
                <!-- Student Navigation -->
                <a href="{{ route('estudiante.dashboard') }}" class="nav-link {{ request()->routeIs('estudiante.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                
                <a href="{{ route('estudiante.notifications.index') }}" class="nav-link {{ request()->routeIs('estudiante.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notificaciones</span>
                    @php
                        $unreadCount = Auth::guard('student')->user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
                
                @if(Auth::guard('student')->user()->semester >= 3)
                <a href="{{ route('estudiante.practices.index') }}" class="nav-link {{ request()->routeIs('estudiante.practices*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</span>
                </a>
                @endif
                
            @elseif(Auth::guard('admin')->check())
                <!-- Admin Navigation -->
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Gestionar Usuarios</span>
                </a>
                
                <a href="{{ route('admin.statistics.index') }}" class="nav-link {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Estadísticas</span>
                </a>
                
            @elseif(Auth::guard('coordinador')->check())
                <!-- Coordinator Navigation -->
                <a href="{{ route('coordinador.dashboard') }}" class="nav-link {{ request()->routeIs('coordinador.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                
                <a href="{{ route('coordinador.professional_practices.index') }}" class="nav-link {{ request()->routeIs('coordinador.professional_practices*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Prácticas Preprofesionales</span>
                </a>
                
                <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="nav-link {{ request()->routeIs('coordinador.student_tutor_assignment*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Asignación de Tutores</span>
                </a>
                
            @elseif(Auth::guard('teacher')->check())
                <!-- Teacher Navigation -->
                <a href="{{ route('profesor.dashboard') }}" class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                
                <a href="{{ route('profesor.professional_practices.index') }}" class="nav-link {{ request()->routeIs('profesor.professional_practices*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Prácticas Preprofesionales</span>
                </a>
                
                @php
                    $teacher = Auth::guard('teacher')->user();
                    $hasAssignedStudents = \App\Models\StudentTutorAssignment::where('teacher_id', $teacher->id)
                        ->where('status', 'active')
                        ->exists();
                @endphp
            @endif
        </div>
    </div>
    
    <div class="navbar-right">
    <div class="user-profile">
        @php
            $userName = 'Usuario';
            $userRole = 'Usuario';
            $profilePhotoUrl = asset('images/default-avatar.svg');
            $profilePhotoRoute = '#';
            $logoutRoute = '#';
            
            if (Auth::guard('student')->check()) {
                $user = Auth::guard('student')->user();
                $userName = $user->name;
                $userRole = 'Estudiante';
                $profilePhotoUrl = \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user);
                $profilePhotoRoute = route('estudiante.profile.photo');
                $logoutRoute = route('estudiante.logout');
            } elseif (Auth::guard('teacher')->check()) {
                $user = Auth::guard('teacher')->user();
                $userName = $user->name;
                $userRole = 'Profesor';
                $profilePhotoUrl = \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user);
                $profilePhotoRoute = route('profesor.profile.photo');
                $logoutRoute = route('profesor.logout');
            } elseif (Auth::guard('coordinador')->check()) {
                $user = Auth::guard('coordinador')->user();
                $userName = $user->name;
                $userRole = 'Coordinador';
                $profilePhotoUrl = \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user);
                $profilePhotoRoute = route('coordinador.profile.photo');
                $logoutRoute = route('coordinador.logout');
            } elseif (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                $userName = $user->name;
                $userRole = 'Administrador';
                $profilePhotoUrl = \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($user);
                $profilePhotoRoute = route('admin.profile.photo');
                $logoutRoute = route('admin.logout');
            }
        @endphp
        
        <div class="profile-photo-container">
            <img src="{{ $profilePhotoUrl }}" 
                 alt="Foto de perfil" 
                 class="profile-photo">
            <div class="profile-overlay">
                <a href="{{ $profilePhotoRoute }}" class="change-photo-btn">
                    <i class="fas fa-camera"></i>
                </a>
            </div>
        </div>
            <div class="user-info">
                <span class="user-name">{{ $userName }}</span>
                <span class="user-role">{{ $userRole }}</span>
            </div>
            
            <!-- User Dropdown Menu -->
            <div class="user-dropdown">
                <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ $profilePhotoRoute }}">
                        <i class="fas fa-user-circle"></i> Mi Foto de Perfil
                    </a></li>
                    @if(Auth::guard('admin')->check())
                        <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="fas fa-cog"></i> Configuración del Sistema
                        </a></li>
                    @elseif(Auth::guard('coordinador')->check())
                        <li><a class="dropdown-item" href="{{ route('coordinador.settings') }}">
                            <i class="fas fa-cog"></i> Configuración
                        </a></li>
                    @elseif(Auth::guard('teacher')->check())
                        <li><a class="dropdown-item" href="{{ route('profesor.settings') }}">
                            <i class="fas fa-cog"></i> Configuración
                        </a></li>
                    @elseif(Auth::guard('student')->check())
                        <li><a class="dropdown-item" href="{{ route('estudiante.settings') }}">
                            <i class="fas fa-cog"></i> Configuración
                        </a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <i class="fas fa-bars"></i>
    </button>
</nav>

<!-- Mobile Navigation Collapse -->
<div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="mobile-nav">
        @if(Auth::guard('student')->check())
            <!-- Student Mobile Navigation -->
            <a href="{{ route('estudiante.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('estudiante.dashboard*') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            
            <a href="{{ route('estudiante.notifications.index') }}" class="mobile-nav-link {{ request()->routeIs('estudiante.notifications*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notificaciones</span>
                @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            
            @if(Auth::guard('student')->user()->semester >= 3)
            <a href="{{ route('estudiante.practices.index') }}" class="mobile-nav-link {{ request()->routeIs('estudiante.practices*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span>{{ Auth::guard('student')->user()->semester >= 4 ? 'Prácticas Profesionales' : 'Prácticas Preprofesionales' }}</span>
            </a>
            @endif
            
        @elseif(Auth::guard('admin')->check())
            <!-- Admin Mobile Navigation -->
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Gestionar Usuarios</span>
            </a>
            
            <a href="{{ route('admin.statistics.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Estadísticas</span>
            </a>
            
        @elseif(Auth::guard('coordinador')->check())
            <!-- Coordinator Mobile Navigation -->
            <a href="{{ route('coordinador.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('coordinador.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            
            <a href="{{ route('coordinador.professional_practices.index') }}" class="mobile-nav-link {{ request()->routeIs('coordinador.professional_practices*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span>Prácticas Preprofesionales</span>
            </a>
            
            <a href="{{ route('coordinador.student_tutor_assignment.index') }}" class="mobile-nav-link {{ request()->routeIs('coordinador.student_tutor_assignment*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span>Asignación de Tutores</span>
            </a>
            
        @elseif(Auth::guard('teacher')->check())
            <!-- Teacher Mobile Navigation -->
            <a href="{{ route('profesor.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            
            <a href="{{ route('profesor.professional_practices.index') }}" class="mobile-nav-link {{ request()->routeIs('profesor.professional_practices*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span>Prácticas Preprofesionales</span>
            </a>
        @endif
        
        <a href="{{ $profilePhotoRoute }}" class="mobile-nav-link {{ request()->routeIs('*.profile.photo') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>Mi Foto de Perfil</span>
        </a>
        
        @if(Auth::guard('admin')->check())
            <a href="{{ route('admin.settings') }}" class="mobile-nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Configuración del Sistema</span>
            </a>
        @elseif(Auth::guard('coordinador')->check())
            <a href="{{ route('coordinador.settings') }}" class="mobile-nav-link {{ request()->routeIs('coordinador.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        @elseif(Auth::guard('teacher')->check())
            <a href="{{ route('profesor.settings') }}" class="mobile-nav-link {{ request()->routeIs('profesor.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        @elseif(Auth::guard('student')->check())
            <a href="{{ route('estudiante.settings') }}" class="mobile-nav-link {{ request()->routeIs('estudiante.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        @endif
        
        <div class="mobile-logout">
            <form id="mobile-logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" class="mobile-nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
</div> 