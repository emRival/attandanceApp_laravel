<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalCard extends BaseWidget
{
    protected static bool $isDiscovered = false;
    public ?string $title = null; // Untuk teks judul
    public $model = null; // Untuk nilai data
    public $database = null;

    protected function getStats(): array
    {
        if (!$this->model) {
            return [];
        }

        $today = Carbon::today(); // Get today's date

        // Total siswa dengan status attend pada hari ini
        $total = $this->database::count();

        // Siswa yang hadir dengan status attend pada hari ini
        $hadir = $this->model::whereDate('date', $today)->where('status', 'attend')
            ->whereDate('date', $today)
            ->count();

        return [
            Stat::make("Total Present $this->title", "$hadir / $total")
                ->description("$this->title present today out of total $this->title")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

        ];
    }
}