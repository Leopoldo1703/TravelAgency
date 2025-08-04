<?php

declare(strict_types=1);

namespace Tests\Feature\Flights;

use Database\Factories\FlightFactory;
use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

use function Pest\Laravel\deleteJson;

it('can delete a flight successfully', function () {
    /** @var Flight $flight */
    $flight = FlightFactory::new()->create();

    deleteJson(url("/api/flights/{$flight->id}"))
        ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
});
