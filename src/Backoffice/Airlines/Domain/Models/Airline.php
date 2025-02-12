<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class Airline extends Model
{
    /**
     * @return HasMany<Flight, Airline>
     */
    public function flights()
    {
        /** @var HasMany<Flight, self> */
        return $this->hasMany(Flight::class, 'origin_city_id');
    }
}
