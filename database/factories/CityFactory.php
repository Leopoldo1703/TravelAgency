<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Backoffice\Cities\Domain\Models\City;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;
    public function definition(): array
    {
        return [
            'name' => fake()->city()
        ];
    }
}
