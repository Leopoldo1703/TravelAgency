<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\App\Controllers;

use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Airlines\App\Requests\UpdateAirlineRequest;
use Lightit\Backoffice\Airlines\Domain\Actions\UpdateAirlineAction;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;

class UpdateAirlineController
{
    public function __invoke(Airline $airline, UpdateAirlineRequest $request, UpdateAirlineAction $action): JsonResponse
    {
        $action->execute($airline, $request->toDto());

        return responder()
            ->success(['message' => 'City updated successfully'])
            ->respond();
    }
}
