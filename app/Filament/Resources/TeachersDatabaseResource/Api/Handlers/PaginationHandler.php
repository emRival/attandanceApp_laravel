<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Models\Teacher;
use App\Filament\Resources\TeachersDatabaseResource;
use App\Models\Student;
use App\Models\StudentsDatabase;
use App\Models\TeachersDatabase;

class PaginationHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = TeachersDatabaseResource::class;

    public function handler()
    {
        // Query untuk data teacher dengan face yang tidak kosong
        $teacherQuery = TeachersDatabase::select('id', 'position', 'face')
            ->whereNotNull('face') // Menampilkan hanya teacher
            ->where('is_active', true); // Menampilkan hanya teacher yang is_active = true

        // Query untuk data student dengan face yang tidak kosong
        $studentQuery = StudentsDatabase::select('id', 'position', 'face')
            ->whereNotNull('face')
            ->where('is_active', true);  // Menampilkan hanya student

        // Gabungkan kedua query menggunakan union
        $query = $teacherQuery->union($studentQuery);

        // Eksekusi query dan ambil hasilnya
        $result = $query->get();  // Mengambil semua data tanpa paginasi

        // Kembalikan hasil dalam bentuk API collection
        return static::getApiTransformer()::collection($result);
    }
}
