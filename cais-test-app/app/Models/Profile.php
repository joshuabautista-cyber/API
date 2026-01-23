<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'tbl_profile';
    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'course_id',
        'dept_id',
        'college_id',
        'fname', 'mname', 'lname',
        'contact_email', 'contact','p_email',
        'address', 'birthdate', 'sex'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'profile_id', 'profile_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }
    
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }
}