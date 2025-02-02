<?php

namespace App\Filament\Resources\GradesResource\Pages;

use App\Filament\Resources\GradesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGrades extends EditRecord
{
    protected static string $resource = GradesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
