<?php

namespace App\Filament\Resources\StudentsAttandanceResource\Pages;

use App\Filament\Resources\StudentsAttandanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentsAttandance extends EditRecord
{
    protected static string $resource = StudentsAttandanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
