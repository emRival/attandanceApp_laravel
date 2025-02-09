<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherProfile extends Model
{
    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($teacherProfile) {
            if (
                $teacherProfile->id
                != 1
            ) { // Pastikan "General" tidak bisa dihapus
                DB::table('times_configs')
                    ->where('teacherprofile_id', $teacherProfile->id)
                    ->update(['teacherprofile_id' => 1]);

                DB::table('teachers_databases')
                    ->where('teacherprofile_id', $teacherProfile->id)
                    ->update(['teacherprofile_id' => 1]);

                DB::table('students_databases')
                    ->where('teacherprofile_id', $teacherProfile->id)
                    ->update(['teacherprofile_id' => 1]);
            }
        });
    }
}
