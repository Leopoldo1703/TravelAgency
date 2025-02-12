<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;

/**
 * @property int                             $id
 * @property int                             $airline_id
 * @property int                             $origin_id
 * @property int                             $destination_id
 * @property string                          $departure
 * @property string                          $arrival
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Airline $airline
 * @property-read City|null $destinationCity
 * @property-read City|null $originCity
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereDeparture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereDestinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereOriginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
