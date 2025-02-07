<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use App\Models\Grades;
use App\Models\StudentsAttandance;
use App\Models\StudentsDatabase;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class StudentsRekap extends Widget implements HasForms
{
    use WithPagination;
    use InteractsWithForms;
    protected static string $view = 'widgets.students-rekap';
    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Rekap Absensi Siswa';
    protected int | string | array $columnSpan = 2;

    public array $filters = [
        'grade' => null,
        'year' => null
    ];

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2) // Grid dengan 2 kolom
                ->schema([
                    Select::make('filters.grade')
                        ->label('Kelas')
                        ->options(Grades::pluck('name', 'id')->toArray())
                        ->placeholder('Pilih Kelas')
                        ->reactive()
                        ->afterStateUpdated(fn () => $this->resetPage()),
                    Select::make('filters.year')
                        ->label('Tahun')
                        ->options(function () {
                            $years = StudentsAttandance::select(DB::raw('YEAR(date) as year'))
                                ->distinct()
                                ->orderBy('year', 'desc')
                                ->pluck('year', 'year')
                                ->toArray();
                            $years[date('Y')] = date('Y');
                            ksort($years);
                            return $years;
                        })
                        ->placeholder('Pilih Tahun')
                        ->default(date('Y'))
                        ->reactive()
                        ->afterStateUpdated(fn () => $this->resetPage()),
                ]),
        ];
    }

    public function render(): View
    {
        $months = [
            'Jan' => 'Januari',
            'Feb' => 'Februari',
            'Mar' => 'Maret',
            'Apr' => 'April',
            'May' => 'Mei',
            'Jun' => 'Juni',
            'Jul' => 'Juli',
            'Aug' => 'Agustus',
            'Sep' => 'September',
            'Oct' => 'Oktober',
            'Nov' => 'November',
            'Dec' => 'Desember',
        ];

        $query = StudentsDatabase::with(['attendances', 'grade'])
            ->when($this->filters['grade'], fn($q) => $q->whereHas('grade', fn($q) => $q->where('id', $this->filters['grade'])))
            ->when($this->filters['year'], fn($q) => $q->whereHas('attendances', fn($q) => $q->whereYear('date', $this->filters['year'])));


        $paginator = $query->paginate(5);

        $students = collect($paginator->items())->map(function ($student) use ($months) {
            $attendanceData = [];

            foreach ($months as $short => $full) {
                $monthAttendance = $student->attendances->filter(
                    fn($a) => Carbon::parse($a->date)->format('M') === $short &&
                        (!$this->filters['year'] || Carbon::parse($a->date)->year == $this->filters['year'])
                );

                $attendanceData[$short] = [
                    'H' => $monthAttendance->where('status', 'attend')->count(),
                    'S' => $monthAttendance->where('status', 'sick')->count(),
                    'A' => $monthAttendance->where('status', 'absent')->count(),
                ];
            }

            return [
                'id' => $student->id,
                'name' => $student->name,
                'grade' => $student->grade?->name,
                'attendance' => $attendanceData
            ];
        });

        return view(static::$view, [
            'months' => $months,
            'students' => $students,
            'paginator' => $paginator,
            'filters' => $this->filters
        ]);
    }
}
