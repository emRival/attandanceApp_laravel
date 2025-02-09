<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsAttandance extends Model
{

        protected $fillable = [
            'student_id',
            'session',
            'date',
            'time',
            'status',
            'captured_image',
            'late',
        ];

        public function student()
        {
            return $this->belongsTo(StudentsDatabase::class, 'student_id');
        }

        public function timeConfig()
        {
            return $this->belongsTo(TimesConfig::class, 'times_config_id');
        }

}