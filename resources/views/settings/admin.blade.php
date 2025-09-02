<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Administrador - Tecnológico Traversari - ISTPET</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboards/admin.dashboard.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar de Navegación -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-placeholder">
    <i class="fas fa-user-shield"></i>
</div>
                <span class="app-name">Tecnológico Traversari - ISTPET</span>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
                    <li><a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>Gestionar Usuarios</span></a></li>
                    <li><a href="#"><i class="fas fa-book"></i> <span>Gestionar Cursos</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Reportes</span></a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> <span>Configuración del Sistema</span></a></li>
                    <li><a href="{{ route('admin.settings') }}" class="active"><i class="fas fa-cog"></i> <span>Configuración</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
                </button>
            </div>
        </aside>

        <!-- Contenido Principal -->
        <main class="main-content">
            <!-- Navbar Superior -->
            <div class="top-navbar">
                <div class="welcome-message">
                    Configuración - {{ $admin->name }}
                </div>
                <div class="user-profile">
                    <div class="profile-photo-container">
                        <img src="{{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($admin) }}" 
                             alt="Foto de perfil" 
                             class="profile-photo">
                        <div class="profile-overlay">
                            <a href="{{ route('admin.profile.photo') }}" class="change-photo-btn">
                                <i class="fas fa-camera"></i>
                            </a>
                        </div>
                    </div>
                    <span>Administrador</span>
                </div>
            </div>

            <!-- Contenido de Configuración -->
            <div class="settings-container">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Sección de Foto de Perfil -->
                <div class="settings-section">
                    <h2><i class="fas fa-user-circle"></i> Foto de Perfil</h2>
                    <div class="profile-photo-settings">
                        <div class="current-photo">
                            <img src="{{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($admin) }}" 
                                 alt="Foto de perfil actual" 
                                 class="settings-profile-photo">
                        </div>
                        <div class="photo-actions">
                            <a href="{{ route('admin.profile.photo') }}" class="btn btn-primary">
                                <i class="fas fa-camera"></i> Cambiar Foto de Perfil
                            </a>
                            @if($admin->profile_photo)
                                <form action="{{ route('admin.profile.photo.remove') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')">
                                        <i class="fas fa-trash"></i> Eliminar Foto
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sección de Información Personal -->
                <div class="settings-section">
                    <h2><i class="fas fa-user"></i> Información Personal</h2>
                    <form action="{{ route('admin.settings.profile') }}" method="POST" class="settings-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre Completo</label>
                            <input type="text" id="name" name="name" value="{{ $admin->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ $admin->email }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>

                <!-- Sección de Cambio de Contraseña -->
                <div class="settings-section">
                    <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
                    <form action="{{ route('admin.settings.password') }}" method="POST" class="settings-form">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>

                <!-- Sección de Preferencias -->
                <div class="settings-section">
                    <h2><i class="fas fa-cog"></i> Preferencias</h2>
                    <div class="preferences">
                        <div class="preference-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Recibir notificaciones por correo electrónico
                            </label>
                        </div>
                        <div class="preference-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Alertas de seguridad del sistema
                            </label>
                        </div>
                        <div class="preference-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Reportes automáticos de actividad
                            </label>
                        </div>
                        <div class="preference-item">
                            <label class="checkbox-label">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Modo oscuro (próximamente)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sección de Configuración del Sistema -->
                <div class="settings-section">
                    <h2><i class="fas fa-server"></i> Configuración del Sistema</h2>
                    <div class="system-settings">
                        <div class="setting-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Mantenimiento automático del sistema
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Respaldos automáticos de base de datos
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="checkbox-label">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Logs de actividad detallados
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="checkbox-label">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Modo de desarrollo (solo para pruebas)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Script para el toggle del sidebar en móviles
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>

    <style>
        /* Estilos específicos para la página de configuración */
        .settings-container {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .settings-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .settings-section h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-photo-settings {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .current-photo {
            text-align: center;
        }

        .settings-profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .photo-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .settings-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-group input {
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-group input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .preferences, .system-settings {
            display: grid;
            gap: 1rem;
        }

        .preference-item, .setting-item {
            display: flex;
            align-items: center;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Estilos para la foto de perfil en la barra superior */
        .profile-photo-container {
            position: relative;
            display: inline-block;
        }

        .profile-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profile-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-photo-container:hover .profile-overlay {
            opacity: 1;
        }

        .change-photo-btn {
            color: white;
            font-size: 14px;
            text-decoration: none;
        }

        .change-photo-btn:hover {
            color: #fff;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-photo-settings {
                flex-direction: column;
                text-align: center;
            }

            .photo-actions {
                flex-direction: row;
                justify-content: center;
            }
        }
    </style>
</body>
</html> 