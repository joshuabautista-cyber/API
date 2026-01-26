<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;
    protected $table = 'tbl_enrollments';

    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'user_id',
        'semester_id',
        'course_id',
        'section',
        'prereg_id',
        'schedId',
        'approval_status',
        'remarks',
        'approved_by',
        'approved_at',
        'registration_only_tag'
    ];

    protected $casts = [
        'approval_status' => 'string',
        'approved_at' => 'datetime',
    ];

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    public function preregistration()
    {
        return $this->belongsTo(Preregistration::class, 'prereg_id', 'prereg_id');
    }

    public function classSchedule()
    {
        return $this->belongsTo(Class_Sched::class, 'schedId', 'schedId');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    /**
     * Scope for pending approvals
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for approved enrollments
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
