<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Profesor - Tecnológico Traversari - ISTPET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/teacher.auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div>
                <h1>Bienvenido al Tecnológico Traversari - ISTPET</h1>
                <p>Plataforma integral para la gestión académica de profesores. Accede a tus clases, tareas y calificaciones de alumnos.</p>
                <div class="features mt-5">
                    <div class="feature-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Gestión de Clases</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-edit"></i>
                        <span>Calificación de Tareas</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-comments"></i>
                        <span>Comunicación con Alumnos</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-logo">
                    <div class="logo-placeholder">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h1 class="logo-text">Tecnológico Traversari ISTPET</h1>
                </div>
                <h2 class="auth-title">Acceso Profesores</h2>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profesor.login.submit') }}" class="auth-form">
                    @csrf

                    <div class="form-group input-with-icon">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tucorreo@ejemplo.com">
                    </div>

                    <div class="form-group input-with-icon">
                        <label for="password" class="form-label">Contraseña</label>
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        <small class="text-muted d-block mt-1 text-end"><a href="{{ route('profesor.password.request') }}">¿Olvidaste tu contraseña?</a></small>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Recordar sesión</label>
                    </div>

                    <button type="submit" class="btn btn-auth">
                        <i class="fas fa-sign-in-alt me-2"></i> Ingresar
                    </button>
                </form>

                <div class="auth-footer">
                    ¿No tienes cuenta? <a href="{{ route('profesor.register') }}">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>