<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssessor extends Model
{
    protected $table = 'users_assessor';
    protected $fillable = [
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