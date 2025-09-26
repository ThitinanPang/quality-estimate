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
}
