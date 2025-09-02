<?php

    namespace App\Models;

    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use App\Models\Subject;
    use App\Models\SubjectTeacher;

    class Teacher extends Authenticatable
    {
        use Notifiable;

        protected $guard = 'teacher'; // Define el guardia para este modelo

        protected $fillable = [
            'name',
            'email',
            'password',
            'teacher_code',
            'profile_photo',
        ];

        protected $hidden = [
            'password',
            'remember_token',
        ];

        public function subjects()
        {
            return $this->belongsToMany(Subject::class);
        }

        /**
         * Get the subject assignments for the teacher.
         */
        public function subjectAssignments()
        {
            return $this->hasMany(SubjectTeacher::class);
        }

        /**
         * Get active subject assignments for the teacher.
         */
        public function activeSubjectAssignments()
        {
            return $this->subjectAssignments()->active();
        }

        /**
         * Get subjects assigned to this teacher for a specific academic period.
         */
        public function getSubjectsByPeriod($period)
        {
            return $this->subjectAssignments()
                ->where('academic_period', $period)
                ->where('status', 'active')
                ->with('subject')
                ->get()
                ->pluck('subject');
        }

        /**
         * Get subjects assigned to this teacher for current period.
         */
        public function getCurrentSubjects()
        {
            $currentPeriod = $this->getCurrentAcademicPeriod();
            return $this->getSubjectsByPeriod($currentPeriod);
        }

        /**
         * Get current academic period.
         */
        public function getCurrentAcademicPeriod()
        {
            $year = date('Y');
            $month = date('n');
            
            // Si estamos en la primera mitad del año, es el segundo semestre del año anterior
            if ($month <= 6) {
                return ($year - 1) . '-' . $year;
            } else {
                return $year . '-' . ($year + 1);
            }
        }

        /**
         * Check if teacher can teach a specific subject.
         */
        public function canTeachSubject($subjectId, $period = null)
        {
            $query = $this->subjectAssignments()
                ->where('subject_id', $subjectId)
                ->where('status', 'active');
            
            if ($period) {
                $query->where('academic_period', $period);
            }
            
            return $query->exists();
        }

        /**
         * Get the grades created by the teacher.
         */
        public function grades()
        {
            return $this->hasMany(Grade::class);
        }

        /**
         * Get the average grade given by the teacher.
         */
        public function getAverageGradeGiven()
        {
            return $this->grades()->avg('grade');
        }

        /**
         * Get the documents where this teacher is assigned as tutor.
         */
        public function tutoredDocuments()
        {
            return $this->hasMany(Document::class, 'tutor_id');
        }

        /**
         * Get the student tutor assignments for this teacher.
         */
        public function studentTutorAssignments()
        {
            return $this->hasMany(StudentTutorAssignment::class, 'teacher_id');
        }

        /**
         * Get the active student tutor assignments for this teacher.
         */
        public function activeStudentTutorAssignments()
        {
            return $this->studentTutorAssignments()->where('status', 'active');
        }

        /**
         * Get the students assigned to this teacher as tutor.
         */
        public function tutoredStudents()
        {
            return $this->belongsToMany(Student::class, 'student_tutor_assignments', 'teacher_id', 'student_id')
                ->wherePivot('status', 'active');
        }

        /**
         * Get teacher's workload (number of active subjects).
         */
        public function getWorkload()
        {
            return $this->activeSubjectAssignments()->count();
        }

        /**
         * Get teacher's workload for a specific period.
         */
        public function getWorkloadByPeriod($period)
        {
            return $this->subjectAssignments()
                ->where('academic_period', $period)
                ->where('status', 'active')
                ->count();
        }
    }
    