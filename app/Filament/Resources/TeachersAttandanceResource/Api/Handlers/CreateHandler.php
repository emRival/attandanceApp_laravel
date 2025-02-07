<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Api\Handlers;

use App\Filament\Resources\TeachersAttandanceResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rupadana\ApiService\Http\Handlers;

use App\Models\Student;
use App\Models\StudentsAttandance;
use App\Models\StudentsDatabase;
use App\Models\Teacher;
use App\Models\TeachersAttandance;
use App\Models\TeachersDatabase;

class CreateHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = TeachersAttandanceResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public function handler(Request $request)
    {
        $attendances = $request->input(); // Ambil semua data sebagai array

        if (!is_array($attendances)) {
            return static::sendErrorResponse("Invalid data format", 400);
        }

        foreach ($attendances as $attendanceData) {
            // Pastikan data memiliki format yang benar
            if (!isset($attendanceData['position'], $attendanceData['id'], $attendanceData['date'], $attendanceData['time'], $attendanceData['times_config_id'])) {
                continue; // Lewati data yang tidak valid
            }

            $position = $attendanceData['position'];
            $id = $attendanceData['id'];
            $date = Carbon::parse($attendanceData['date'])->toDateString();
            $time = $attendanceData['time'];
            $timesConfigId = $attendanceData['times_config_id'];
            $capturedImage = $attendanceData['captured_image'] ?? null;

            if ($position === 'student') {
                $this->handleAttendance(
                    StudentsAttandance::class,
                    StudentsDatabase::class,
                    'student_id',
                    $id,
                    $date,
                    $time,
                    $timesConfigId,
                    $capturedImage
                );
            } elseif ($position === 'teacher') {
                $this->handleAttendance(
                    TeachersAttandance::class,
                    TeachersDatabase::class,
                    'teacher_id',
                    $id,
                    $date,
                    $time,
                    $timesConfigId,
                    $capturedImage
                );
            }
        }

        return static::sendSuccessResponse([], "All attendances processed successfully");
    }

    private function handleAttendance($attendanceModel, $userModel, $userIdField, $userId, $date, $time, $timesConfigId, $capturedImage)
    {
        // Periksa apakah ada data di tanggal ini
        $existingAttendance = $attendanceModel::where('date', $date)
            ->where('times_config_id', $timesConfigId)
            ->first(); 

        if (!$existingAttendance) {
            // Data pertama kali masuk di tanggal ini
            $users = $userModel::all();

            foreach ($users as $user) {
                $attendanceModel::create([
                    $userIdField => $user->id,
                    'time' => ($user->id == $userId) ? $time : null,
                    'times_config_id' => $timesConfigId,
                    'date' => $date,
                    'status' => ($user->id == $userId) ? 'attend' : 'absent',
                    'captured_image' => ($user->id == $userId) ? $capturedImage : null,
                ]);
            }
        } else {
            // Perbarui data untuk pengguna tertentu
            $attendanceModel::updateOrCreate(
                [
                    $userIdField => $userId,
                    'date' => $date,
                ],
                [
                    'time' => $time,
                    'times_config_id' => $timesConfigId,
                    'captured_image' => $capturedImage,
                    'status' => 'attend',
                ]
            );
        }
    }
}