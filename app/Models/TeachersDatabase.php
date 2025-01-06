<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachersDatabase extends Model
{
    protected $fillable = [
        'name',
        'class',
        'nip',
        'position',
        'is_active',
        'face'
    ];

    public function attendances()
    {
        return $this->hasMany(TeachersAttandance::class, 'teacher_id');
    }

    public function faceRecognations()
    {
        return $this->hasMany(GalleryRecognation::class, 'teacher_id');
    }

    // count face recognation
    public function getCountFaceRecognationAttribute()
    {
        return $this->faceRecognations()->count();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'teacher_id');
    }
}
