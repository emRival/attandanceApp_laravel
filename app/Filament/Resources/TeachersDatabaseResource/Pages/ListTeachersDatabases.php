<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Pages;

use App\Filament\Resources\TeachersDatabaseResource;
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
}
