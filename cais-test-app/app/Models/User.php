<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;
use App\Models\Registration;
use App\Models\Teaching_Loads;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;
    
    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
    'uname',      
    'upass',      
    'email',
    'profile_id',
    'status',
    'user_type',   // Changed from 'user_type'
];

    protected $hidden = [
        'upass',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'upass' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->upass;
    }

    // Relationships
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'user_id', 'user_id');
    }

    
    public function gradesReceived(): HasMany
    {
        return $this->hasMany(Grades::class, 'user_id', 'user_id');
    }

    public function gradesAssigned(): HasMany
    {
        return $this->hasMany(Grades::class, 'faculty_id', 'user_id');
    }
}

