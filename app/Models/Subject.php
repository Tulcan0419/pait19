<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'credits',
        'academic_year',
        'semester',
        'curricular_unit',
        'career',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    /**
     * Get the subject assignments for this subject.
     */
    public function subjectAssignments()
    {
        return $this->hasMany(SubjectTeacher::class);
    }

    /**
     * Get the grades for this subject.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the average grade for this subject.
     */
    public function getAverageGrade()
    {
        return $this->grades()->avg('grade');
    }

    /**
     * Scope para filtrar por carrera
     */
    public function scopeByCareer($query, $career)
    {
        return $query->where('career', $career);
    }

    /**
     * Scope para filtrar por año académico
     */
    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope para filtrar por semestre
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope para materias hasta un semestre específico
     */
    public function scopeUpToSemester($query, $semester)
    {
        return $query->where('semester', '<=', $semester);
    }

    /**
     * Scope para filtrar por unidad curricular
     */
    public function scopeByCurricularUnit($query, $unit)
    {
        return $query->where('curricular_unit', $unit);
    }

    /**
     * Scope para materias activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener el nombre de la unidad curricular en español
     */
    public function getCurricularUnitNameAttribute()
    {
        $units = [
            'basica' => 'Básica',
            'profesional' => 'Profesional',
            'integracion' => 'Integración Curricular'
        ];
        
        return $units[$this->curricular_unit] ?? $this->curricular_unit;
    }

    /**
     * Obtener el color de la unidad curricular
     */
    public function getCurricularUnitColorAttribute()
    {
        $colors = [
            'basica' => '#87CEEB', // Azul claro
            'profesional' => '#FFA500', // Naranja
            'integracion' => '#90EE90' // Verde claro
        ];
        
        return $colors[$this->curricular_unit] ?? '#CCCCCC';
    }
}
