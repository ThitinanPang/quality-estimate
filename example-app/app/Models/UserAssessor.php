<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class UserAssessor extends Authenticatable
{
    use Notifiable;
    protected $table = 'users_assessor';
    protected $fillable = [
        'code_assessor',
        'prefix',
        'name',
        'subject_group',
        'faculty',
        'course',
        'email',
        'role',
        'phone_number',
        'status',
        'assessor_type',
        'training_type',
    ];
}