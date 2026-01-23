<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Class_Sched as ClassSchedule;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grades extends Model
{
    use HasFactory;

    protected $table = 'tbl_grades';
    protected $primaryKey = 'grade_id';

    protected $fillable = [
        'semester_id',
        'user_id',
        'faculty_id',
        'subject_id',
        'schedId',
        'units',
        'grades',
        'remarks',
        'weight',
        'reexam',
        'pending_status',
        'day',
        'time',
        'room',
        'date_uploaded',
        'date_faculty_approved',
        'date_dept_approved',
        'date_dean_approved',
        'dept_id',
    ];

    /**
     * Relationship to the Enrollment table.
     * Note: Migration maps 'semester_id' column to 'enrollments_id' on tbl_enrollments.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'semester_id', 'enrollments_id');
    }

    /**
     * Relationship to the Student (User).
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship to the Faculty/Instructor (User).
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'faculty_id', 'user_id');
    }

    /**
     * Relationship to Class Schedule (via subject_id).
     */
    public function subject(): BelongsTo
    {
        // Based on migration: subject_id references tbl_class_scheds.schedId
        return $this->belongsTo(ClassSchedule::class, 'subject_id', 'schedId');
    }

    /**
     * Relationship to Class Schedule (via schedId).
     */
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedId', 'schedId');
    }

    /**
     * Relationship to Department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    
}