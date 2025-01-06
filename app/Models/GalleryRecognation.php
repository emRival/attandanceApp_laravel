<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryRecognation extends Model
{

    protected $fillable = [
        'student_id',
        'teacher_id',
        'image',
    ];

    public function student()
    {
        return $this->belongsTo(StudentsDatabase::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(TeachersDatabase::class, 'teacher_id');
    }



}