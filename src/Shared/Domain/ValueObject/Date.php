<?php

declare(strict_types=1);

namespace Booking\Shared\Domain\ValueObject;

use Booking\Shared\Domain\Exception\InvalidValueException;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

class Date
{
    private const TIMEZONE = 'UTC';

    private const ISO8601_DATE_ONLY = 'Y-m-d';

    private DateTimeImmutable $date;

    /**
     * @throws InvalidValueException
     */
    public function __construct(?string $date = null)
    {
        try {
            $this->date = $date
                ?
                new DateTimeImmutable($date)
                :
                new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE));
        } catch (Exception) {
            throw new InvalidValueException("Invalid date value {$date}");
        }
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function stringDate(): string
    {
        return $this->date
            ->format(self::ISO8601_DATE_ONLY)
        ;
    }

    public function modify(string $modifier): self
    {
        $this->date = (new DateTimeImmutable())->modify($modifier);

        return $this;
    }


    public function year(): int
    {
        return (int) $this->date->format('Y');
    }

    public function month(): int
    {
        return (int) $this->date->format('m');
    }

    public function diffInDays(Date $date): int
    {
        return $this->date->diff($date->date)->d;
    }

    public function diffInYears(Date $date): int
    {
        return $this->date->diff($date->date)->y;
    }
}
