import './bootstrap';
import Alpine from 'alpinejs';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Funcionalidad de navegación móvil
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        // Ocultar el menú inicialmente en móvil
        mobileMenu.style.display = 'none';
        
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Alternar estado del botón
            this.setAttribute('aria-expanded', !isExpanded);
            this.classList.toggle('active');
            
            // Alternar visibilidad del menú
            if (isExpanded) {
                mobileMenu.style.display = 'none';
                mobileMenu.classList.remove('show');
            } else {
                mobileMenu.style.display = 'block';
                mobileMenu.classList.add('show');
            }
        });
        
        // Cerrar el menú al hacer clic en un enlace
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.style.display = 'none';
                mobileMenu.classList.remove('show');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenuButton.classList.remove('active');
            });
        });
        
        // Cerrar el menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.style.display = 'none';
                mobileMenu.classList.remove('show');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenuButton.classList.remove('active');
            }
        });
    }
});
