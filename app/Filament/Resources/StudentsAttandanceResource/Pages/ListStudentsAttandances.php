<?php

namespace App\Filament\Resources\StudentsAttandanceResource\Pages;

use App\Filament\Resources\StudentsAttandanceResource;
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
}
