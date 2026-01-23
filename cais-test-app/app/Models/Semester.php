<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Class_Sched as ClassSchedule; 
use App\Models\Teaching_Loads;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'tbl_semester';
    protected $primaryKey = 'semester_id';

    protected $fillable = [
        'semester_name', 'semester_no','semester_year',
        'semester_status','grades_deadline',
        
    ];

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'semester_id', 'semester_id');
    }


}