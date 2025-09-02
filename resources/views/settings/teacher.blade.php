@extends('layouts.teacher-dashboard')

@section('title', 'Configuración - Profesor - Tecnológico Traversari - ISTPET')

@push('styles')
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

        .preferences {
            display: grid;
            gap: 1rem;
        }

        .preference-item {
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
@endpush

@section('content')
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
                            <img src="{{ \App\Http\Controllers\ProfilePhotoController::getProfilePhotoUrl($teacher) }}" 
                                 alt="Foto de perfil actual" 
                                 class="settings-profile-photo">
                        </div>
                        <div class="photo-actions">
                            <a href="{{ route('profesor.profile.photo') }}" class="btn btn-primary">
                                <i class="fas fa-camera"></i> Cambiar Foto de Perfil
                            </a>
                            @if($teacher->profile_photo)
                                <form action="{{ route('profesor.profile.photo.remove') }}" method="POST" style="display: inline;">
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
                    <form action="{{ route('profesor.settings.profile') }}" method="POST" class="settings-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre Completo</label>
                            <input type="text" id="name" name="name" value="{{ $teacher->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ $teacher->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="teacher_code">Código de Profesor</label>
                            <input type="text" id="teacher_code" value="{{ $teacher->teacher_code ?? 'No asignado' }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>

                <!-- Sección de Cambio de Contraseña -->
                <div class="settings-section">
                    <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
                    <form action="{{ route('profesor.settings.password') }}" method="POST" class="settings-form">
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
                
            </div>
@endsection 