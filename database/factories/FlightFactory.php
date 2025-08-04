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
        /**
         * @var Airline
         */
        $airline = Airline::inRandomOrder()->first() ?: AirlineFactory::new()->create();
        /**
         * @var City
         */
        $origin = City::inRandomOrder()->first() ?: CityFactory::new()->create();
        /**
         * @var City
         */
        $destination = City::where('id', '!=', $origin->id)->inRandomOrder()->first() ?: CityFactory::new()->create();
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
