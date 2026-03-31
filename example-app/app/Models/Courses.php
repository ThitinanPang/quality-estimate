<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table = 'courses';

    // ฟิลด์ที่อนุญาตให้กรอกข้อมูลได้
    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'level',
    ];
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
     public function courseAssessor()
    {
        return $this->hasOne(CourseAssessor::class, 'course_id'); 
        // หรือ return $this->belongsTo(CourseAssessor::class, 'assessor_id'); 
        // ขึ้นอยู่กับว่า foreign key อยู่ที่ table ไหนครับ
    }
}
