<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Pages;

use App\Filament\Resources\TeachersDatabaseResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTeachersDatabase extends EditRecord
{
    protected static string $resource = TeachersDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('presensi')
                ->label('Upload Image')
                ->icon('heroicon-o-plus-circle')
                ->url(route('faces.index', ['role' => $this->record->position, 'id' => $this->record->id])),
            Actions\DeleteAction::make(),
        ];
    }
}
