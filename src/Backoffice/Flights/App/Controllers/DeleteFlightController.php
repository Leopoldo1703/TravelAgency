<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Flights\Domain\Actions\DeleteFlightAction;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class DeleteFlightController
{
    public function __invoke(Flight $flight, DeleteFlightAction $action): JsonResponse
    {
        $action->execute($flight);

        return responder()
            ->success()
            ->respond(JsonResponse::HTTP_NO_CONTENT);
    }
}
