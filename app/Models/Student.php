<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Document;
use App\Models\Subject;

class Student extends Authenticatable
{
    use Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_code',
        'career',
        'semester',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the documents for the student.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the tutor assignments for the student.
     */
    public function tutorAssignments()
    {
        return $this->hasMany(StudentTutorAssignment::class);
    }

    /**
     * Get the active tutor assignment for the student.
     */
    public function activeTutorAssignment()
    {
        return $this->hasOne(StudentTutorAssignment::class)->where('status', 'active');
    }

    /**
     * Get the current tutor for the student.
     */
    public function currentTutor()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')
            ->whereHas('tutorAssignments', function($query) {
                $query->where('student_id', $this->id)->where('status', 'active');
            });
    }

    /**
     * Get the subjects for the student.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * Get the grades for the student.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the average grade for the student.
     */
    public function getAverageGrade()
    {
        return $this->grades()->avg('grade');
    }

    /**
     * Get the average grade for a specific subject.
     */
    public function getAverageGradeBySubject($subjectId)
    {
        return $this->grades()->where('subject_id', $subjectId)->avg('grade');
    }
}
