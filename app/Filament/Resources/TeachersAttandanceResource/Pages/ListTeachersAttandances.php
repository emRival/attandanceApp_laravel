<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Pages;

use App\Filament\Resources\TeachersAttandanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachersAttandances extends ListRecords
{
    protected static string $resource = TeachersAttandanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Teacher Attandance'),
        ];
    }
}