<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $table = 'tbl_college';

    protected $primaryKey = 'college_id';

    protected $fillable = [
        'college_name',
        'college_desc'
    ];
}