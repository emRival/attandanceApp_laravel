<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsDatabase extends Model
{
    protected $fillable = [
        'name',
        'class',
        'nis',
        'position',
        'is_active',
        'face'
    ];

    public function attendances()
    {
        return $this->hasMany(StudentsAttandance::class, 'student_id');
    }

    public function faceRecognations()
    {
        return $this->hasMany(GalleryRecognation::class, 'student_id');
    }

    // count face recognation
    public function getCountFaceRecognationAttribute()
    {
        return $this->faceRecognations()->count();
    }
}
