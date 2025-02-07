<?php

namespace App\Filament\Resources\StudentsDatabaseResource\Pages;

use App\Filament\Resources\StudentsDatabaseResource;
use App\Filament\Widgets\TotalActiveUserCard;
use App\Models\StudentsDatabase;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentsDatabases extends ListRecords
{
    protected static string $resource = StudentsDatabaseResource::class;

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
                'title' => 'Students',
                'database' => StudentsDatabase::class,
            ]),
        ];
    }
}