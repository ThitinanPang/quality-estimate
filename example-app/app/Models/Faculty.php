<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculty'; // ชื่อตารางจริงใน DB
    protected $fillable = ['name','subject_group']; // คอลัมน์ที่อนุญาตให้ mass assign
}
