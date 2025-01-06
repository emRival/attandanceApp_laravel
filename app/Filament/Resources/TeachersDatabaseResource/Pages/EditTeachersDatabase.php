<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Pages;

use App\Filament\Resources\TeachersDatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachersDatabase extends EditRecord
{
    protected static string $resource = TeachersDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
