<?php

namespace App\Filament\Resources\TimesConfigResource\Pages;

use App\Filament\Resources\TimesConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimesConfig extends EditRecord
{
    protected static string $resource = TimesConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
