<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

/**
 * @property int                             $id
 * @property string                          $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Flight> $arrivals
 * @property-read int|null $arrivals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Flight> $departures
 * @property-read int|null $departures_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Airline> $airlines
 * @property-read int|null $airlines_count
 *
 * @mixin \Eloquent
 */
class City extends Model
{
    protected $fillable = ['name'];

    /**
     * @return HasMany<Flight, $this>
     */
    public function departures()
    {
        return $this->hasMany(Flight::class, 'origin_id');
    }

    /**
     * @return HasMany<Flight, $this>
     */
    public function arrivals()
    {
        return $this->hasMany(Flight::class, 'destination_id');
    }

    /**
     * @return BelongsToMany<Airline, $this>
     */
    public function airlines()
    {
        return $this->belongsToMany(Airline::class, 'airline_city');
    }
}
