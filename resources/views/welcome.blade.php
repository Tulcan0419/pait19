<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tecnológico Traversari - ISTPET
        Sistema de Gestión Educativa Integral</title>
    <meta name="description"
        content="Plataforma integral para la gestión académica de estudiantes, profesores y administradores. Accede a cursos, tareas, calificaciones y más.">
    <meta name="keywords"
        content="gestión educativa, sistema académico, estudiantes, profesores, administradores, Tecnológico Traversari - ISTPET, plataforma educativa">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlace al archivo CSS externo personalizado -->
    <link rel="stylesheet" href="{{ asset('css/welcome.style.css') }}">
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap mr-2"></i>Tecnológico Traversari - ISTPET
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#acceso">Acceso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sección Hero -->
    <section id="hero" class="hero-section d-flex align-items-center">
        <div class="container text-center">
            <h1 class="hero-title animate__animated animate__fadeInDown">Sistema de Gestion de Practicas Profesionales</h1>
            <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-0.5s">Plataforma integral para la
                gestión</p>
            <div class="mt-5 animate__animated animate__zoomIn animate__delay-1s">
            </div>
        </div>
    </section>


    <!-- Sección de tarjetas de acceso -->
    <section id="acceso" class="container py-5">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">Acceso Rápido a tu Rol</h2>
            <p class="text-muted lead">Selecciona tu perfil para iniciar sesión y gestionar tus actividades.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="icon-container student-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5 class="card-title">Estudiante</h5>
                        <p class="card-text">Sube Tus documentos de prácticas preprofesionales aquí.</p>
                        <a href="{{ route('estudiante.login') }}" class="btn btn-primary mt-auto">Iniciar Sesión</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="icon-container teacher-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h5 class="card-title">Profesor</h5>
                        <p class="card-text">Gestiona tus prácticas preprofesionales aquí.</p>
                        <a href="{{ route('profesor.login') }}" class="btn btn-success mt-auto">Iniciar Sesión</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="icon-container admin-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5 class="card-title">Administrador</h5>
                        <p class="card-text">Administra el sistema, gestiona usuarios.</p>
                        <a href="{{ route('admin.login') }}" class="btn btn-danger mt-auto">Iniciar Sesión</a>
                    </div>
                </div>
            </div>

            {{-- NUEVO: Tarjeta de acceso para Coordinador --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="icon-container coordinator-icon">
                            <i class="fas fa-user-tie"></i> {{-- Icono para coordinador --}}
                        </div>
                        <h5 class="card-title">Coordinador</h5>
                        <p class="card-text">Accede a la gestión de prácticas preprofesionales.</p>
                        <a href="{{ route('coordinador.login') }}" class="btn btn-info mt-auto">Iniciar Sesión</a> {{-- Botón con ruta al login del coordinador --}}
                    </div>
                </div>
            </div>
            {{-- FIN NUEVO --}}

        </div>
    </section>


    <!-- Sección de características -->
    
    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4"><i class="fas fa-graduation-cap mr-2"></i>Tecnológico Traversari - ISTPET</h5>
                    <p class="text-muted">El Sistema de Gestión de Prácticas Profesionales del Tecnológico Traversari - ISTPET es una plataforma web integral desarrollada para centralizar, optimizar y digitalizar todos los procesos relacionados con las prácticas preprofesionales de los estudiantes.</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-4">Enlaces Rápidos</h5>
                    <div class="footer-links d-flex flex-column">
                        <a href="#hero">Inicio</a>
                        <a href="#acceso">Acceso</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-4">Conéctate con Nosotros</h5>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/institutotraversari/?locale=es_LA" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/tecnologico_traversari?fbclid=IwY2xjawMjM_RleHRuA2FlbQIxMABicmlkETFXd0JINkFmanE0MXNURnJlAR7XhMkZ7eSvFazwgbcIJ2wwhtuf-Q7aOga8edzeV6yQ7mQ5M4MzmXwFDR4DHw_aem_NWev_ZrVk-0b7Q0IvyvFLQ" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                    <p class="text-muted mt-3">Email: info@istpet.com</p>
                    <p class="text-muted">Tel: +123 456 7890</p>
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="col-md-12 text-center">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} Tecnológico Traversari - ISTPET. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts JS de Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script para smooth scrolling (opcional, si no usas un framework JS) -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>