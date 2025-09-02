<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class StudentNotificationController extends Controller
{
    /**
     * Muestra todas las notificaciones del estudiante.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Debug: Verificar si el estudiante está autenticado
        if (!$student) {
            abort(401, 'Estudiante no autenticado');
        }
        
        $notifications = $student->notifications()->paginate(10);
        
        // Debug: Log para verificar las notificaciones
        \Log::info('Student notifications count: ' . $notifications->count());
        \Log::info('Student ID: ' . $student->id);
        
        return view('auth.student.notifications.index', compact('notifications'));
    }

    /**
     * Marca una notificación como leída.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $student = Auth::guard('student')->user();
        
        // Verificar que la notificación pertenece al estudiante
        if ($notification->notifiable_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para acceder a esta notificación.'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true, 'message' => 'Notificación marcada como leída.']);
    }

    /**
     * Marca todas las notificaciones como leídas.
     */
    public function markAllAsRead()
    {
        $student = Auth::guard('student')->user();
        
        // Marcar todas las notificaciones no leídas como leídas
        $student->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Todas las notificaciones han sido marcadas como leídas.']);
    }

    /**
     * Elimina una notificación.
     */
    public function destroy(DatabaseNotification $notification)
    {
        $student = Auth::guard('student')->user();
        
        // Verificar que la notificación pertenece al estudiante
        if ($notification->notifiable_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para eliminar esta notificación.'], 403);
        }
        
        $notification->delete();
        
        return response()->json(['success' => true, 'message' => 'Notificación eliminada.']);
    }

    /**
     * Obtiene el número de notificaciones no leídas (para AJAX).
     */
    public function getUnreadCount()
    {
        $student = Auth::guard('student')->user();
        $count = $student->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
} 