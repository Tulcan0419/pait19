<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'document_type',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'comments',
        'status',
        'teacher_status',
        'teacher_comments',
        'coordinator_status',
        'coordinator_comments',
        'hours_completed',
    ];

    /**
     * Get the student that owns the document.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the tutor (teacher) assigned to this document.
     */
    public function tutor()
    {
        return $this->belongsTo(Teacher::class, 'tutor_id');
    }

    /**
     * Get the active tutor for the student who owns this document.
     */
    public function activeTutor()
    {
        return $this->hasOneThrough(
            Teacher::class,
            StudentTutorAssignment::class,
            'student_id', // Foreign key on student_tutor_assignments table
            'id', // Foreign key on teachers table
            'student_id', // Local key on documents table
            'teacher_id' // Local key on student_tutor_assignments table
        )->where('student_tutor_assignments.status', 'active');
    }
}

