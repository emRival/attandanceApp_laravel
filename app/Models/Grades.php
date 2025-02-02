<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    protected $fillable = [
        'name'
    ];

    public function students()
    {
        return $this->hasMany(StudentsDatabase::class, 'class_id');
    }
}
