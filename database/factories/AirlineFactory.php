<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;

/**
 * @extends Factory<Airline>
 */
class AirlineFactory extends Factory
{
    protected $model = Airline::class;
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->text()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Airline $airline) {
            $cities = City::inRandomOrder()->limit(5)->pluck('id');

            if ($cities->count() < 5) {
                $needed = 5 - $cities->count();
                $newCities = CityFactory::new()->count($needed)->create()->pluck('id');
                $cities = $cities->merge($newCities);
            }

            $airline->cities()->attach($cities);
        });
    }
}
