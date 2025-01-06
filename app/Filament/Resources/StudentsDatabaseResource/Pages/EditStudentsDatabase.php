<?php

namespace App\Filament\Resources\StudentsDatabaseResource\Pages;

use App\Filament\Resources\StudentsDatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentsDatabase extends EditRecord
{
    protected static string $resource = StudentsDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
