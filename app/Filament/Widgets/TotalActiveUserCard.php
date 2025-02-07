<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalActiveUserCard extends BaseWidget
{
   
    public ?string $title = null; // Title text
    protected static bool $isDiscovered = false;
    public ?string $database = null; // Model class name

    protected function getStats(): array
    {
        // Ensure the model is set and exists
        if (!$this->database || !class_exists($this->database)) {
            return [];
        }

        // Total students in the database
        $total = $this->database::count();

        // Active students
        $active = $this->database::where('is_active', '1')->count();

        return [
            Stat::make("Total Active $this->title", "$active / $total")
                ->description("$active $this->title active of $total")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}