<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessment';

    // ฟิลด์ที่อนุญาตให้กรอกข้อมูลได้
    protected $fillable = [
        'name',
        'faculty',
        'courses',
        'criterion',
        'result',
        'strength',
        'improvement',
        'score',
        'overall',
    ];

    // แปลง score เป็น array อัตโนมัติ
    protected $casts = [
        'score' => 'array',
        'overall' => 'array',
    ];
    //     public function faculty()
    // {
    //     return $this->belongsTo(Faculty::class);
    // }
}
