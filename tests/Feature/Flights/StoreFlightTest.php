<?php

declare(strict_types=1);

namespace Tests\Feature\Flights;

use Carbon\Carbon;
use Database\Factories\AirlineFactory;
use Database\Factories\CityFactory;
use Illuminate\Http\JsonResponse;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Lightit\Backoffice\Flights\Domain\Models\Flight;
use function Pest\Laravel\postJson;

describe('flights', function () {
    it('can store a flight successfully', function () {
        /** @var City $origin */
        $origin = CityFactory::new()->create();
        /** @var City $destination */
        $destination = CityFactory::new()->create();

        /** @var Airline $airline */
        $airline = AirlineFactory::new()->create();

        $departure = Carbon::now()->addDays(2)->format('Y-m-d H:i');
        $arrival = Carbon::now()->addDays(2)->addHours(3)->format('Y-m-d H:i');

        $payload = [
            'airline_id'     => $airline->id,
            'origin_id'      => $origin->id,
            'destination_id' => $destination->id,
            'departure'      => $departure,
            'arrival'        => $arrival,
        ];

        $response = postJson(url('/api/flights'), $payload)
            ->assertCreated()
            ->assertJson([
                'status'  => JsonResponse::HTTP_CREATED,
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'airline_id',
                    'airline_name',
                    'origin_id',
                    'origin_name',
                    'destination_id',
                    'destination_name',
                    'departure',
                    'arrival',
                ],
            ]);

        /**
         * @var array{
         *   departure: string,
         *   arrival: string,
         *   airline_id: int,
         *   airline_name: string,
         *   origin_id: int,
         *   origin_name: string,
         *   destination_id: int,
         *   destination_name: string
         * } $data
         */
        $data = $response->json('data');

        expect(Carbon::parse($data['departure'])->format('Y-m-d H:i'))
            ->toBe(Carbon::parse($departure)->format('Y-m-d H:i'));
        expect(Carbon::parse($data['arrival'])->format('Y-m-d H:i'))
            ->toBe(Carbon::parse($arrival)->format('Y-m-d H:i'));

        expect($data['airline_id'])->toBe($payload['airline_id']);
        expect($data['airline_name'])->toBe($airline->name);
        expect($data['origin_id'])->toBe($payload['origin_id']);
        expect($data['origin_name'])->toBe($origin->name);
        expect($data['destination_id'])->toBe($payload['destination_id']);
        expect($data['destination_name'])->toBe($destination->name);

        expect(Flight::count())->toBe(1);
    });


    it('fails if the airline does not operate in the selected cities', function () {
        /** @var Airline $airline */
        $airline = AirlineFactory::new()->create();
        /** @var City $origin */
        $origin = CityFactory::new()->create();
        /** @var City $destination */
        $destination = CityFactory::new()->create();

        $departure = Carbon::now()->addDays(2)->format('Y-m-d H:i');
        $arrival = Carbon::now()->addDays(2)->addHours(3)->format('Y-m-d H:i');

        $payload = [
            'airline_id'     => $airline->id,
            'origin_id'      => $origin->id,
            'destination_id' => $destination->id,
            'departure'      => $departure,
            'arrival'        => $arrival,
        ];

        postJson(url('/api/flights'), $payload)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status'  => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'success' => false,
                'error'   => [
                    'code'    => 'validation_failed',
                    'message' => 'The given data failed to pass validation.',
                    'fields'  => [
                        'cities' => [
                            'This airline does not operate in one or both selected cities.',
                        ],
                    ],
                ],
            ]);

        expect(Flight::count())->toBe(0);
    });

    it('fails if required fields are missing', function () {
        postJson(url('/api/flights'), [])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status'  => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'success' => false,
                'error'   => [
                    'code'    => 'validation_failed',
                    'message' => 'The given data failed to pass validation.',
                    'fields'  => [
                        'airline_id'     => ['The airline id field is required.'],
                        'origin_id'      => ['The origin id field is required.'],
                        'destination_id' => ['The destination id field is required.'],
                        'departure'      => ['The departure field is required.'],
                        'arrival'        => ['The arrival field is required.'],
                    ],
                ],
            ]);
    });

    it('fails if arrival is before departure', function () {
        /** @var City $origin */
        $origin = CityFactory::new()->create();
        /** @var City $destination */
        $destination = CityFactory::new()->create();
        /** @var Airline $airline */
        $airline = AirlineFactory::new()->create();

        $departure = Carbon::now()->addDays(2)->format('Y-m-d H:i');
        $arrival = Carbon::now()->addDays(1)->format('Y-m-d H:i');

        $payload = [
            'airline_id'     => $airline->id,
            'origin_id'      => $origin->id,
            'destination_id' => $destination->id,
            'departure'      => $departure,
            'arrival'        => $arrival,
        ];

        postJson(url('/api/flights'), $payload)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status'  => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'success' => false,
                'error'   => [
                    'code'    => 'validation_failed',
                    'message' => 'The given data failed to pass validation.',
                    'fields'  => [
                        'arrival' => ['The arrival field must be a date after departure.'],
                    ],
                ],
            ]);

        expect(Flight::count())->toBe(0);
    });
});
