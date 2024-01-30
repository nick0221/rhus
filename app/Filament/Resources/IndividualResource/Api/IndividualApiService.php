<?php
namespace App\Filament\Resources\IndividualResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\IndividualResource;
use Illuminate\Routing\Router;


class IndividualApiService extends ApiService
{
    protected static string | null $resource = IndividualResource::class;



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
