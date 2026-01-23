<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;
    protected $table = 'tbl_department';

    protected $primaryKey = 'dept_id';

    protected $fillable = ['college_id','college_name','dept_name','dept_desc'];

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }
}
