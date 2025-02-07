<?php

namespace App\Filament\Resources\StudentsAttandanceResource\Pages;

use App\Filament\Resources\StudentsAttandanceResource;
use App\Filament\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Widgets\TotalCard;
use App\Models\StudentsAttandance;
use App\Models\StudentsDatabase;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentsAttandances extends ListRecords
{
    protected static string $resource = StudentsAttandanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            TotalCard::make([
                'title' => 'Students',
                'model' => StudentsAttandance::class,
                'database' => StudentsDatabase::class,
            ]),
        ];
    }
}
