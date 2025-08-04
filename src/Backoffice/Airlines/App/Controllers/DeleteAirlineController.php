<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Airlines\Domain\Actions\DeleteAirlineAction;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;

class DeleteAirlineController
{
    public function __invoke(Airline $airline, DeleteAirlineAction $action): JsonResponse
    {
        $action->execute($airline);

        return responder()
            ->success()
            ->respond(JsonResponse::HTTP_NO_CONTENT);
    }
}
