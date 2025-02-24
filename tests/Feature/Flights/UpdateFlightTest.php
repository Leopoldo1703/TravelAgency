<?php

declare(strict_types=1);

namespace Tests\Feature\Flights;

use Carbon\Carbon;
use Database\Factories\AirlineFactory;
use Database\Factories\CityFactory;
use Database\Factories\FlightFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

use function Pest\Laravel\putJson;

it('can update a flight successfully', function () {
    /** @var Flight $flight */
    $flight = FlightFactory::new()->create();

    /** @var Airline $newAirline */
    $newAirline = AirlineFactory::new()->create();
    /** @var City $newOrigin */
    $newOrigin = CityFactory::new()->create();
    /** @var City $newDestination */
    $newDestination = CityFactory::new()->create();

    $newAirline->cities()->attach([$newOrigin, $newDestination]);

    $newDeparture = Carbon::now()->addDays(10)->format('Y-m-d H:i:s');
    $newArrival = Carbon::now()->addDays(10)->addHours(2)->format('Y-m-d H:i:s');

    $payload = [
        'airline_id'     => $newAirline->id,
        'origin_id'      => $newOrigin->id,
        'destination_id' => $newDestination->id,
        'departure'      => $newDeparture,
        'arrival'        => $newArrival,
    ];

    putJson(url("/api/flights/{$flight->id}"), $payload)
        ->assertSuccessful()
        ->assertJson(
            fn (AssertableJson $json) =>
                $json->where('status', JsonResponse::HTTP_OK)
                     ->where('success', true)
                     ->has(
                         'data',
                         fn (AssertableJson $json) =>
                        $json->where('id', $flight->id)
                             ->where('airline_id', $newAirline->id)
                             ->where('airline_name', $newAirline->name)
                             ->where('origin_id', $newOrigin->id)
                             ->where('origin_name', $newOrigin->name)
                             ->where('destination_id', $newDestination->id)
                             ->where('destination_name', $newDestination->name)
                             ->where('departure', $newDeparture)
                             ->where('arrival', $newArrival)
                     )
        );

    $flight->refresh();
    expect($flight->airline_id)->toBe($newAirline->id);
    expect($flight->origin_id)->toBe($newOrigin->id);
    expect($flight->destination_id)->toBe($newDestination->id);
    expect($flight->departure->format('Y-m-d H:i:s'))->toBe($newDeparture);
    expect($flight->arrival->format('Y-m-d H:i:s'))->toBe($newArrival);
});
