<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;

class Flight extends Model
{
    /**
     * @return BelongsTo<Airline, Flight>
     */
    public function airline()
    {
        /** @var BelongsTo<Airline, self> */
        return $this->belongsTo(Airline::class);
    }

    /**
     * @return BelongsTo<City, Flight>
     */
    public function originCity()
    {
        /** @var BelongsTo<City, self> */
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    /**
     * @return BelongsTo<City, Flight>
     */
    public function destinationCity()
    {
        /** @var BelongsTo<City, self> */
        return $this->belongsTo(City::class, 'destination_city_id');
    }
}
