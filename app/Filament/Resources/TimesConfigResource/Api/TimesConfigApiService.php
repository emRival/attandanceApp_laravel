<?php

namespace App\Filament\Resources\TimesConfigResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TimesConfigResource;
use Illuminate\Routing\Router;


class TimesConfigApiService extends ApiService
{
    protected static string | null $resource = TimesConfigResource::class;

    public static function handlers(): array
    {
        return [
            // Handlers\CreateHandler::class,
            // Handlers\UpdateHandler::class,
            // Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            // Handlers\DetailHandler::class
        ];
    }
}