<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'grade',
        'type',
        'title',
        'comments',
        'evaluation_date'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'grade' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Scope para obtener calificaciones por tipo
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope para obtener calificaciones por materia
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    // Scope para obtener calificaciones por estudiante
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Método para obtener el promedio de un estudiante en una materia
    public static function getAverageByStudentAndSubject($studentId, $subjectId)
    {
        return self::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->avg('grade');
    }

    // Método para obtener el promedio general de un estudiante
    public static function getGeneralAverage($studentId)
    {
        return self::where('student_id', $studentId)->avg('grade');
    }
}
