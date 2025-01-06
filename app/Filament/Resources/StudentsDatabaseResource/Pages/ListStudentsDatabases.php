<?php

namespace App\Filament\Resources\StudentsDatabaseResource\Pages;

use App\Filament\Resources\StudentsDatabaseResource;
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
}
