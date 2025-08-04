<?php

declare(strict_types=1);

namespace Tests\Feature\Flights;

use Database\Factories\FlightFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

use function Pest\Laravel\getJson;

it('can get a flight successfully', function () {
    /** @var Flight */
    $flight = FlightFactory::new()->create();
    /** @var Airline */
    $airline = $flight->airline;
    /** @var City */
    $originCity = $flight->originCity;
    /** @var City */
    $destinationCity = $flight->destinationCity;

    getJson(url("/api/flights/{$flight->id}"))
        ->assertSuccessful()
        ->assertJson(
            fn (AssertableJson $json) =>
                $json->where('status', JsonResponse::HTTP_OK)
                     ->where('success', true)
                     ->has(
                         'data',
                         fn (AssertableJson $json) =>
                         $json->where('id', $flight->id)
                              ->where('airline_id', $flight->airline_id)
                              ->where('airline_name', $airline->name)
                              ->where('origin_id', $flight->origin_id)
                              ->where('origin_name', $originCity->name)
                              ->where('destination_id', $flight->destination_id)
                              ->where('destination_name', $destinationCity->name)
                              ->where('departure', $flight->departure->format('Y-m-d H:i:s'))
                              ->where('arrival', $flight->arrival->format('Y-m-d H:i:s'))
                     )
        );
});
