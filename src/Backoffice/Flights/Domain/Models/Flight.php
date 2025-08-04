<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;

/**
 * @property int                             $id
 * @property int                             $airline_id
 * @property int                             $origin_id
 * @property int                             $destination_id
 * @property Carbon                          $departure
 * @property Carbon                          $arrival
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
 * @property int $Lightit\Backoffice\Airlines\Domain\Models\Airline
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereLightit\Backoffice\Airlines\Domain\Models\Airline($value)
 *
 * @mixin \Eloquent
 */
class Flight extends Model
{
    protected $fillable = [
        'airline_id',
        'origin_id',
        'destination_id',
        'departure',
        'arrival',
    ];

    protected $casts = [
        'departure' => 'datetime',
        'arrival' => 'datetime',
    ];

    /**
     * @return BelongsTo<Airline, $this>
     */
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function originCity()
    {
        return $this->belongsTo(City::class, 'origin_id');
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function destinationCity()
    {
        return $this->belongsTo(City::class, 'destination_id');
    }
}
