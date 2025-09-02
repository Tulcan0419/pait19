<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Document;

class DocumentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $status;
    protected $comments;
    protected $reviewerType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, string $status, string $reviewerType, ?string $comments = null)
    {
        $this->document = $document;
        $this->status = $status;
        $this->comments = $comments;
        $this->reviewerType = $reviewerType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Temporarily disable email notifications
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText();
        $reviewerText = $this->getReviewerText();
        
        $message = (new MailMessage)
            ->subject("Documento {$statusText} - {$this->document->document_type}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu documento **{$this->document->document_type}** ha sido **{$statusText}** por el {$reviewerText}.")
            ->line("**Detalles del documento:**")
            ->line("- Tipo: {$this->document->document_type}")
            ->line("- Archivo: {$this->document->file_name}")
            ->line("- Fecha de revisión: " . now()->format('d/m/Y H:i'));

        if ($this->comments) {
            $message->line("**Comentarios del {$reviewerText}:**")
                   ->line($this->comments);
        }

        if ($this->status === 'rejected') {
            $message->line("Por favor, revisa los comentarios y realiza las correcciones necesarias antes de volver a subir el documento.");
        } elseif ($this->status === 'approved') {
            $message->line("¡Felicidades! Tu documento ha sido aprobado. Continúa con el siguiente paso de tus prácticas profesionales.");
        }

        $message->action('Ver Documentos', url('/student/professional-practices'))
                ->line("Gracias por usar nuestro sistema de prácticas profesionales.");

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'file_name' => $this->document->file_name,
            'status' => $this->status,
            'reviewer_type' => $this->reviewerType,
            'comments' => $this->comments,
            'reviewed_at' => now(),
            'message' => "Tu documento {$this->document->document_type} ha sido {$this->getStatusText()} por el {$this->getReviewerText()}"
        ];
    }

    /**
     * Get the status text in Spanish.
     */
    private function getStatusText(): string
    {
        return match($this->status) {
            'approved' => 'aprobado',
            'rejected' => 'rechazado',
            'pending' => 'pendiente',
            default => $this->status
        };
    }

    /**
     * Get the reviewer text in Spanish.
     */
    private function getReviewerText(): string
    {
        return match($this->reviewerType) {
            'teacher' => 'profesor',
            'coordinator' => 'coordinador',
            default => $this->reviewerType
        };
    }
} 