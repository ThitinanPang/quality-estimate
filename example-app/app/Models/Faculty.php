<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculty'; // ชื่อตารางจริงใน DB
    protected $fillable = ['name', 'campus']; // คอลัมน์ที่อนุญาตให้ mass assign
    public function courses()
    {
        return $this->hasMany(\App\Models\Courses::class, 'faculty_id', 'id');
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
