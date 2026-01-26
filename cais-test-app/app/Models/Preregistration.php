<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preregistration extends Model
{
    use HasFactory;

    protected $table = 'tbl_preregistration';

    protected $primaryKey = 'prereg_id';

    protected $fillable = [
        'user_id',
        'semester_id',
        'course_id',
        'schedId',
        'section',
        'subject_code',
        'subject_title',
        'units',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the preregistration
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the semester for this preregistration
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    /**
     * Get the course for this preregistration
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Get the class schedule for this preregistration
     */
    public function classSchedule()
    {
        return $this->belongsTo(Class_Sched::class, 'schedId', 'schedId');
    }

    /**
     * Scope for pending preregistrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for enrolled preregistrations
     */
    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    /**
     * Scope for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for a specific semester
     */
    public function scopeForSemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }
}
