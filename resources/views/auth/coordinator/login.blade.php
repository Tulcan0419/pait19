<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Coordinador - Tecnológico Traversari - ISTPET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/coordinator.auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div>
                <h1>Sistema de Gestión Académica</h1>
                <p>Acceso exclusivo para coordinadores académicos del Tecnológico Traversari</p>
                <div class="features mt-4">
                    <div class="feature-item">
                        <i class="fas fa-tasks"></i>
                        <span>Gestión académica</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>Administración docente</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-logo">
                    <div class="logo-placeholder">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h1 class="logo-text">Tecnológico Traversari ISTPET</h1>
                </div>
                <h2 class="auth-title">Acceso Coordinadores</h2>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        @foreach($errors->all() as $error)
                            <p class="mb-0"><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</p>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('coordinador.login.submit') }}" class="auth-form" id="coordinatorLoginForm">
                    @csrf

                    <div class="form-group input-with-icon">
                        <label for="email" class="form-label">Correo Institucional</label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tucorreo@ejemplo.com">
                    </div>

                    <div class="form-group input-with-icon">
                        <label for="password" class="form-label">Contraseña</label>
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Recordar sesión</label>
                    </div>

                    <button type="submit" class="btn btn-auth">
                        <i class="fas fa-sign-in-alt me-2"></i> Ingresar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('coordinatorLoginForm');
            const csrfToken = document.querySelector('input[name="_token"]');
            
            console.log('Formulario encontrado:', !!form);
            console.log('Token CSRF encontrado:', !!csrfToken);
            
            if (form && csrfToken) {
                console.log('Token CSRF valor:', csrfToken.value);
                
                form.addEventListener('submit', function(e) {
                    console.log('Formulario enviado');
                    console.log('Token CSRF en envío:', csrfToken.value);
                });
            }
        });
    </script>
</body>
</html>