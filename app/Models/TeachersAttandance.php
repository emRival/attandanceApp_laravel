<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachersAttandance extends Model
{
    protected $fillable = [
        'teacher_id',
        'times_config_id',
        'date',
        'time',
        'status',
        'captured_image',
    ];

    public function teacher()
    {
        return $this->belongsTo(TeachersDatabase::class, 'teacher_id');
    }

    public function timeConfig()
    {
        return $this->belongsTo(TimesConfig::class, 'times_config_id');
    }
}