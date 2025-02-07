<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Pages;

use App\Filament\Resources\TeachersAttandanceResource;
use App\Filament\Widgets\TotalCard;
use App\Models\TeachersAttandance;
use App\Models\TeachersDatabase;
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

    protected function getHeaderWidgets(): array
    {
        if (!auth()->user()->hasRole('super_admin')) {
            return [];
        }

        return [
            TotalCard::make([
                'title' => 'Teachers',
                'model' => TeachersAttandance::class,
                'database' => TeachersDatabase::class,
            ]),
        ];
    }
}