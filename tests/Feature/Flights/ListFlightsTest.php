<?php

declare(strict_types=1);

namespace Tests\Feature\Flights;

use Database\Factories\AirlineFactory;
use Database\Factories\CityFactory;
use Database\Factories\FlightFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

use function Pest\Laravel\getJson;

describe('flights', function () {
    it('can list flights successfully', function () {
        /** @var Airline $airline */
        $airline = AirlineFactory::new()->create();
        /** @var City $origin */
        $origin = CityFactory::new()->create();
        /** @var City $destination */
        $destination = CityFactory::new()->create();

        /** @var Collection<int, Flight> $flights */
        $flights = FlightFactory::new()->count(15)->create([
            'airline_id'    => $airline->id,
            'origin_id'     => $origin->id,
            'destination_id'=> $destination->id,
        ]);

        /** @var Flight $firstFlight */
        $firstFlight = $flights->first();

        /** @var City $originCity */
        $originCity = $firstFlight->originCity;
        /** @var City $destinationCity */
        $destinationCity = $firstFlight->destinationCity;

        getJson(url('/api/flights'))
            ->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('status', JsonResponse::HTTP_OK)
                    ->where('success', true)
                    ->has('data', 10)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) =>
                        $json->where('id', $firstFlight->id)
                            ->where('airline_id', $firstFlight->airline_id)
                            ->where('airline_name', $firstFlight->airline->name)
                            ->where('origin_id', $firstFlight->origin_id)
                            ->where('origin_name', $originCity->name)
                            ->where('destination_id', $firstFlight->destination_id)
                            ->where('destination_name', $destinationCity->name)
                            ->where('departure', $firstFlight->departure->format('Y-m-d H:i:s'))
                            ->where('arrival', $firstFlight->arrival->format('Y-m-d H:i:s'))
                    )
                    ->has('pagination')
                    ->where('pagination.count', 10)
                    ->where('pagination.total', 15)
                    ->where('pagination.perPage', 10)
                    ->where('pagination.currentPage', 1)
                    ->where('pagination.totalPages', 2)
            );
    });
});
