<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateAirlineController
{
    public function __invoke(Request $request): JsonResponse
    {
        return responder()
            ->success()
            ->respond();
    }
}
