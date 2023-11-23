<?php

namespace Booking\Booking\ActiveBooking\Infrastructure\PMS;

use Booking\Booking\ActiveBooking\Domain\PMSFaker\PMSFakerRepository;
use Booking\Shared\Domain\Exception\InternalErrorException;
use Booking\Shared\Domain\ValueObject\Date;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;

class HttpPMSFakerRepository implements PMSFakerRepository
{
    private const PMS_URI = 'https://cluster-dev.stay-app.com/sta/pms-faker/stay/test/pms?ts=%s';

    private const DEFAULT_TIMESTAMP = 0;

    public function __construct(
        private readonly Client $client
    ) {
    }

    /**
     * @throws InternalErrorException
     * @throws \JsonException
     * @throws \Exception
     */
    public function findAllSinceTimeStamp(?Date $date): array
    {
        try {
            $response = $this->client->request(
                'GET',
                sprintf(self::PMS_URI, $this->getTimestamp($date))
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }

        if (200 !== $response->getStatusCode()) {
            throw new InternalErrorException($response->getBody()->getContents());
        }

        $arrayResponse = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return $arrayResponse['bookings'];
    }

    /**
     * @throws \Exception
     */
    private function getTimestamp(?Date $date): int
    {
        if ($date === null) {
            return self::DEFAULT_TIMESTAMP;
        }

        return $date->date()->getTimestamp();
    }
}
