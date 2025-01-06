<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Pages;

use App\Filament\Resources\TeachersAttandanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachersAttandance extends EditRecord
{
    protected static string $resource = TeachersAttandanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
