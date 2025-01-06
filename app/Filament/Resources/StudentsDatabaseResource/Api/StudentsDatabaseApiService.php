<?php
namespace App\Filament\Resources\StudentsDatabaseResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\StudentsDatabaseResource;
use Illuminate\Routing\Router;


class StudentsDatabaseApiService extends ApiService
{
    protected static string | null $resource = StudentsDatabaseResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
