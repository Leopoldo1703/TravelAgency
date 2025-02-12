<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class City extends Model
{
    /**
     * @return HasMany<Flight, City>
     */
    public function departures()
    {
        /** @var HasMany<Flight, self> */
        return $this->hasMany(Flight::class, 'origin_city_id');
    }

    /**
     * @return HasMany<Flight, City>
     */
    public function arrivals()
    {
        /** @var HasMany<Flight, self> */
        return $this->hasMany(Flight::class, 'origin_city_id');
    }
}
