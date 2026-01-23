<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Class_Sched as ClassSchedule; 

class Registration extends Model
{
    use HasFactory;

    protected $table = 'tbl_registration';
    protected $primaryKey = 'registration_id';

    protected $fillable = [
        'user_id',
        'schedId',
        'enrollment_id',
        'enrollment_type',
        'ra_status',
        'status',
        'ra_date_approved',
        'forced_drop',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'enrollment_id');
    }

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedId', 'schedId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}