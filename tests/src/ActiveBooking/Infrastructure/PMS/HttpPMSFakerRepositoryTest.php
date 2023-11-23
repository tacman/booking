<?php

namespace Booking\Tests\ActiveBooking\Infrastructure\PMS;

use Booking\Booking\ActiveBooking\Infrastructure\PMS\HttpPMSFakerRepository;
use Booking\Shared\Domain\Exception\InternalErrorException;
use Booking\Shared\Domain\ValueObject\Date;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HttpPMSFakerRepositoryTest extends TestCase
{
    private const PMS_URI = 'https://cluster-dev.stay-app.com/sta/pms-faker/stay/test/pms?ts=%s';

    private HttpPMSFakerRepository $httpRepository;

    private Client|MockObject $client;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->httpRepository = new HttpPMSFakerRepository(
            $this->client
        );
    }

    public function testShouldFindAllSinceTimeStamp(): void
    {
        $date = new Date();
        $response = new Response(
            200,
            [],
            $this->expectedResponse()
        );

        $this->client->expects(self::once())
            ->method('request')
            ->with('GET', sprintf(self::PMS_URI, $date->date()->getTimestamp()))
            ->willReturn($response);

        $this->httpRepository->findAllSinceTimestamp($date);
    }

    public function testShouldThrowExceptionWhenStatusCodeIsNot200(): void
    {
        $response = new Response(
            400,
            [],
            ''
        );

        $this->client->expects(self::once())
            ->method('request')
            ->with('GET', sprintf(self::PMS_URI, 0))
            ->willReturn($response);
        $this->expectException(InternalErrorException::class);

        $this->httpRepository->findAllSinceTimestamp(null);
    }

    public function testShouldThrowExceptionWhenClientFails(): void
    {
        $date = new Date();

        $guzzleException = $this->createMock(ClientException::class);

        $this->client->expects(self::once())
            ->method('request')
            ->with('GET', sprintf(self::PMS_URI, $date->date()->getTimestamp()))
            ->willThrowException($guzzleException);

        $this->expectException(InternalErrorException::class);

        $this->httpRepository->findAllSinceTimestamp($date);
    }

    private function expectedResponse()
    {
        return '
        {"bookings":
            [{
                "hotel_id": "49001",
                "hotel_name": "Hotel con ID Externo 49001",
                "guest": {
                    "name": "Juan",
                    "lastname": "Madrigal",
                    "birthdate": "1999-12-06",
                    "passport": "WF-1495889-GR",
                    "country": "ES"
                },
                "booking": {
                    "locator": "61F80321790C5",
                    "room": "291",
                    "check_in": "2022-01-31",
                    "check_out": "2022-02-08",
                    "pax": {
                        "adults": 1,
                        "kids": 0,
                        "babies": 0
                    }
                },
                "created": "2022-01-31 17:39:38",
                "signature": "e8b558125c709621bd5a80ca25f772cc7a3a4b8b0b86478f355740af5d7558a8"
            }],
            "total": 1
          }
        ';
    }
}
