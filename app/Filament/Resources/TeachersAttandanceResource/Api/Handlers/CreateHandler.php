<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Api\Handlers;

use App\Filament\Resources\TeachersAttandanceResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rupadana\ApiService\Http\Handlers;
use App\Models\TeachersAttandance;
use App\Models\TeachersDatabase;
use App\Models\TimesConfig;
use App\Models\StudentsAttandance;
use App\Models\StudentsDatabase;

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
        $attendances = $request->all();

        if (!is_array($attendances)) {
            return static::sendErrorResponse("Invalid data format", 400);
        }

        foreach ($attendances as $attendanceData) {
            if (!isset(
                $attendanceData['position'],
                $attendanceData['id'],
                $attendanceData['date'],
                $attendanceData['time'],
                $attendanceData['times_config_id'],
                $attendanceData['late']
            )) {
                continue;
            }

            $position = $attendanceData['position'];
            $idUser = $attendanceData['id'];
            $date = Carbon::parse($attendanceData['date'])->toDateString();
            $timesConfigId = $attendanceData['times_config_id'];

            if ($position === 'teacher') {
                $this->handleTeacherAttendance(
                    attendanceData: $attendanceData,
                    date: $date,
                    idUser: $idUser,
                    timesConfigId: $timesConfigId
                );
            }

            if ($position === 'student') {
                $this->handleStudentAttendance(
                    attendanceData: $attendanceData,
                    date: $date,
                    idUser: $idUser,
                    timesConfigId: $timesConfigId
                );
            }
        }

        return static::sendSuccessResponse([], "Attendance processed successfully");
    }

    private function handleTeacherAttendance(array $attendanceData, string $date, int $idUser, int $timesConfigId)
    {
        // Validasi guru dan time config
        $teacher = TeachersDatabase::find($idUser);
        $timeConfig = TimesConfig::find($timesConfigId);

        // Validasi kecocokan time config dengan profile_id
        if (!$teacher || !$timeConfig || $timeConfig->teacherprofile_id != $teacher->teacherprofile_id) {
            return static::sendErrorResponse("Invalid time configuration for this teacher", 400);
        }

        // Cek apakah ini data pertama untuk tanggal ini
        $isFirstEntry = !TeachersAttandance::where('date', $date)->exists() &&
            !StudentsAttandance::where('date', $date)->exists();

        // Proses data absensi utama
        $this->processMainAttendance(
            teacher: $teacher,
            timeConfig: $timeConfig,
            attendanceData: $attendanceData,
            date: $date
        );

        // Jika ini data pertama, buat semua entri absent
        if ($isFirstEntry) {
            $this->createAllDefaultEntries($date);
        }
    }

    private function handleStudentAttendance(array $attendanceData, string $date, int $idUser, int $timesConfigId)
    {
        // Validasi siswa dan time config
        $student = StudentsDatabase::find($idUser);
        $timeConfig = TimesConfig::find($timesConfigId);

        // Validasi kecocokan time config dengan profile_id
        if (!$student || !$timeConfig || $timeConfig->teacherprofile_id != $student->teacherprofile_id) {
            return static::sendErrorResponse("Invalid time configuration for this teacher", 400);
        }

        // Cek apakah ini data pertama untuk tanggal ini
        $isFirstEntry = !TeachersAttandance::where('date', $date)->exists() &&
            !StudentsAttandance::where('date', $date)->exists();

        // Proses data absensi utama
        $this->processMainStudentAttendance(
            student: $student,
            timeConfig: $timeConfig,
            attendanceData: $attendanceData,
            date: $date
        );

        // Jika ini data pertama, buat semua entri absent
        if ($isFirstEntry) {
            $this->createAllDefaultEntries($date);
        }
    }

    private function processMainAttendance($teacher, $timeConfig, $attendanceData, $date)
    {
        // Cek existing data
        $existingAttendance = TeachersAttandance::where([
            'teacher_id' => $teacher->id,
            'date' => $date,
            'session' => $timeConfig->name
        ])->first();

        // hanya bisa update jika statusnya absent
        if ($existingAttendance && $existingAttendance->status !== 'absent') {
            return;
        }

        // Update atau create data
        TeachersAttandance::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'date' => $date,
                'session' => $timeConfig->name
            ],
            [
                'time' => $attendanceData['time'],
                'captured_image' => $attendanceData['captured_image'] ?? null,
                'status' => $attendanceData['late'] == 0 ? 'attend' : 'late',
                'late' => $attendanceData['late'],
            ]
        );
    }
    private function processMainStudentAttendance($student, $timeConfig, $attendanceData, $date)
    {
        // Cek existing data
        $existingAttendance = StudentsAttandance::where([
            'student_id' => $student->id,
            'date' => $date,
            'session' => $timeConfig->name
        ])->first();

        // hanya bisa update jika statusnya absent
        if ($existingAttendance && $existingAttendance->status !== 'absent') {
            return;
        }

        // Update atau create data
        StudentsAttandance::updateOrCreate(
            [
                'student_id' => $student->id,
                'date' => $date,
                'session' => $timeConfig->name
            ],
            [
                'time' => $attendanceData['time'],
                'captured_image' => $attendanceData['captured_image'] ?? null,
                'status' => $attendanceData['late'] == 0 ? 'attend' : 'late',
                'late' => $attendanceData['late'],
            ]
        );
    }

    private function createAllDefaultEntries(string $date)
    {
        // Handle semua guru
        $teachers = TeachersDatabase::all();
        foreach ($teachers as $teacher) {
            $timeConfigs = TimesConfig::where('teacherprofile_id', $teacher->teacherprofile_id)->get();
            foreach ($timeConfigs as $config) {
                TeachersAttandance::firstOrCreate([
                    'teacher_id' => $teacher->id,
                    'date' => $date,
                    'session' => $config->name
                ], [
                    'times_config_id' => $config->id,
                    'status' => 'absent',
                    'time' => null,
                    'captured_image' => null,
                    'late' => 0
                ]);
            }
        }

        // Handle semua siswa (contoh implementasi)
        $students = StudentsDatabase::all();
        foreach ($students as $student) {
            $timeConfigs = TimesConfig::where('teacherprofile_id', $student->teacherprofile_id)->get();
            foreach ($timeConfigs as $config) {
                StudentsAttandance::firstOrCreate([
                    'student_id' => $student->id,
                    'date' => $date,
                    'session' => $config->name
                ], [

                    'status' => 'absent',
                    'time' => null,
                    'captured_image' => null,
                    'late' => 0
                ]);
            }
        }
    }
}
