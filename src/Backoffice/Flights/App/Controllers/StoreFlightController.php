<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreFlightController
{
    public function __invoke(Request $request): JsonResponse
    {
        return responder()
            ->success()
            ->respond();
    }
}
