<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Airlines\Domain\Actions\ListCitiesFromAirlineAction;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\App\Transformers\CityTransformer;

class ListCitiesFromAirlineController
{
    public function __invoke(ListCitiesFromAirlineAction $action, Airline $airline): JsonResponse
    {
        $cities = $action->execute($airline);

        return responder()
            ->success($cities, CityTransformer::class)
            ->respond();
    }
}
