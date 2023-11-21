<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit;

use Booking\Shared\Domain\Bus\Event\EventBus;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

abstract class PhpUnitTestCase extends MockeryTestCase
{
    private $eventBus;

    protected function mock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    /**
     * @return EventBus|MockInterface
     */
    protected function eventBus(): MockInterface
    {
        return $this->eventBus = $this->eventBus ?: $this->mock(EventBus::class);
    }
}
