@extends('layouts.student-dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
<style>
/* Inline styles to test CSS loading */
.notifications-container {
    background: #f8f9fa !important;
    min-height: 100vh !important;
    padding: 20px 0 !important;
}

.notification-item.unread {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
    border-left: 4px solid #2196f3 !important;
}

.notification-item.read {
    background: #ffffff !important;
    opacity: 0.8 !important;
}

.notification-indicator {
    width: 8px !important;
    height: 8px !important;
    background: #2196f3 !important;
    border-radius: 50% !important;
    flex-shrink: 0 !important;
}

.empty-state-icon {
    width: 96px !important;
    height: 96px !important;
    background: #f8f9fa !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 24px !important;
}
</style>
@endpush

@section('content')
<div class="notifications-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm">
                    <!-- Header -->
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                    <div>
                                <h1 class="h3 mb-1 text-dark">Notificaciones</h1>
                                <p class="text-muted mb-0">Gestiona tus notificaciones del sistema</p>
                </div>
            @if($notifications->count() > 0)
                            <div>
                                <button onclick="markAllAsRead()" class="btn btn-primary btn-sm">
                                    <i class="fas fa-check-double me-2"></i>Marcar todas como leídas
                    </button>
                    </div>
            @endif
            </div>
        </div>

        <!-- Notifications List -->
                    <div class="card-body p-0">
        @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                            <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }} p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="notification-content flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            @if(!$notification->read_at)
                                                <div class="notification-indicator me-3"></div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="notification-message fw-medium">
                                                        @if(isset($notification->data['message']))
                                                            {{ $notification->data['message'] }}
                                    @else
                                                            Notificación del sistema
                                    @endif
                                                </span>
                                        @if(!$notification->read_at)
                                                        <span class="badge bg-primary ms-2">Nuevo</span>
                                        @endif
                                                </div>
                                                <p class="notification-time text-muted small mb-0">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notification-actions ms-3">
                                        @if(!$notification->read_at)
                                        <button onclick="markAsRead('{{ $notification->id }}')" class="btn btn-link btn-sm text-primary p-0 me-3">
                                                    Marcar como leída
                                                </button>
                                        @endif
                                        <button onclick="deleteNotification('{{ $notification->id }}')" class="btn btn-link btn-sm text-danger p-0">
                                                Eliminar
                                            </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-bell fa-3x text-muted"></i>
                                </div>
                                <h3 class="empty-state-title h5 mb-2">No tienes notificaciones</h3>
                                <p class="empty-state-description text-muted">Cuando recibas notificaciones importantes, aparecerán aquí.</p>
                        </div>
                        @endif
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
                </div>
            @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for AJAX operations -->
<script>
function showMessage(message, type = 'success') {
    // Crear un toast o alert temporal
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    // Remover automáticamente después de 3 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

function markAsRead(notificationId) {
    const button = event.target;
    const originalText = button.textContent;
    
    button.classList.add('btn-loading');
    button.disabled = true;
    button.textContent = 'Procesando...';
    
    fetch(`/estudiante/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showMessage('Notificación marcada como leída correctamente');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Error al marcar como leída', 'error');
            button.classList.remove('btn-loading');
            button.disabled = false;
            button.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error al procesar la solicitud', 'error');
        button.classList.remove('btn-loading');
        button.disabled = false;
        button.textContent = originalText;
    });
}

function markAllAsRead() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.classList.add('btn-loading');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
    
    fetch('/estudiante/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showMessage('Todas las notificaciones han sido marcadas como leídas');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Error al marcar todas como leídas', 'error');
            button.classList.remove('btn-loading');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error al procesar la solicitud', 'error');
        button.classList.remove('btn-loading');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function deleteNotification(notificationId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
        const button = event.target;
        const originalText = button.textContent;
        
        button.classList.add('btn-loading');
        button.disabled = true;
        button.textContent = 'Eliminando...';
        
        fetch(`/estudiante/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage('Notificación eliminada correctamente');
                setTimeout(() => location.reload(), 1000);
            } else {
                showMessage(data.message || 'Error al eliminar la notificación', 'error');
                button.classList.remove('btn-loading');
                button.disabled = false;
                button.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al procesar la solicitud', 'error');
            button.classList.remove('btn-loading');
            button.disabled = false;
            button.textContent = originalText;
        });
    }
}
</script>
@endsection 