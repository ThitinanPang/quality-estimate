<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssessor extends Model
{
    protected $table = 'course_assessors';
    protected $fillable = [
        'course_id',
        'user_id',
        'faculty_id',
        'campus',
        'subject_group',
        'education_level',
        'assessment_type',
        'chairperson',
        'position',
        'intern',
        'assessment_date',
        'secretary'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Courses::class);
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}