<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'subject_teacher';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'academic_period',
        'status',
        'comments',
        'max_students',
    ];

    protected $casts = [
        'max_students' => 'integer',
    ];

    /**
     * Get the teacher for this assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject for this assignment.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para filtrar por período académico
     */
    public function scopeByAcademicPeriod($query, $period)
    {
        return $query->where('academic_period', $period);
    }

    /**
     * Scope para filtrar por profesor
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope para filtrar por materia
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Obtener el nombre del estado en español
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'pending' => 'Pendiente'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
} 