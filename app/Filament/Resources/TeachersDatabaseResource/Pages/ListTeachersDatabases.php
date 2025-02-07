<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Pages;

use App\Filament\Resources\TeachersDatabaseResource;
use App\Filament\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Widgets\TotalActiveUserCard;
use App\Models\TeachersDatabase;
use EightyNine\FilamentAdvancedWidget\AdvancedWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachersDatabases extends ListRecords
{
    protected static string $resource = TeachersDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalActiveUserCard::make([
                'title' => 'Teachers',
                'database' => TeachersDatabase::class,
            ]),
        ];
    }


}