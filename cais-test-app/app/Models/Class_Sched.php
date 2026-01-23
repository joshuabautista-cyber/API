<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\College;

class Class_Sched extends Model
{
    /** @use HasFactory<\Database\Factories\ClassSchedFactory> */
    use HasFactory;
    protected $table = 'tbl_class_schedules';
    
    // REQUIRED: Define your custom primary key from the migration
    protected $primaryKey = 'schedId'; 

    protected $fillable = [
        'schedId','semester_id','subject_code',
        'course_id','cat_no','subject_title','units',
        'atl','time','date','section','room','slot_no',
        'class_type','lab_type','dept_id','weight','bulk_upload'
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'schedId', 'schedId');
    }

     public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
    

    
}
