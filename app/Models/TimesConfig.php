<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesConfig extends Model
{
    protected $fillable = [
        'name',
        'start',
        'end',
        'late',
    ];

    public function teachers()
    {
        return $this->hasMany(TeachersAttandance::class, 'time_config_id');
    }

    public function students()
    {
        return $this->hasMany(StudentsAttandance::class, 'time_config_id');
    }

    // teacher profile
    public function teacherProfile()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacherprofile_id');
    }
}