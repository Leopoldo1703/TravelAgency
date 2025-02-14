<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\App\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Lightit\Backoffice\Flights\Domain\DataTransferObjects\FlightDto;

class StoreFlightRequest extends FormRequest
{
    public const AIRLINE_ID = 'airline_id';

    public const ORIGIN_ID = 'origin_id';

    public const DESTINATION_ID = 'destination_id';

    public const DEPARTURE = 'departure';

    public const ARRIVAL = 'arrival';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::AIRLINE_ID => ['required', 'exists:airlines,id'],
            self::ORIGIN_ID => ['required', 'exists:cities,id'],
            self::DESTINATION_ID => ['required', 'exists:cities,id'],
            self::DEPARTURE => ['required', 'date_format:Y-m-d H:i'],
            self::ARRIVAL => ['required', 'date_format:Y-m-d H:i', 'after:' . self::DEPARTURE],
        ];
    }

    public function toDto(): FlightDto
    {
        /** @var string $departure */
        $departure = $this->input(self::DEPARTURE);

        /** @var string $arrival */
        $arrival = $this->input(self::ARRIVAL);

        return new FlightDto(
            airlineId: $this->integer(self::AIRLINE_ID),
            originId: $this->integer(self::ORIGIN_ID),
            destinationId: $this->integer(self::DESTINATION_ID),
            departure: Carbon::parse($departure),
            arrival: Carbon::parse($arrival)
        );
    }
}
