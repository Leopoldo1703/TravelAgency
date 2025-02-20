<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

/**
 * @extends Factory<Flight>
 */
class FlightFactory extends Factory
{
    protected $model = Flight::class;
    public function definition(): array
    {
        $airline = Airline::inRandomOrder()->firstOrFail();
        $origin = City::inRandomOrder()->firstOrFail();
        $destination = City::where('id', '!=', $origin->id)->inRandomOrder()->firstOrFail();
        $departure = fake()->dateTimeBetween('now', '+1 month');
        $arrival = fake()->dateTimeBetween($departure, $departure->modify('+2 days'));

        return [
            'airline_id' => $airline->id,
            'origin_id' => $origin->id,
            'destination_id' => $destination->id,
            'departure' => $departure,
            'arrival' => $arrival
        ];
    }
}
