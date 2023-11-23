<?php

declare(strict_types=1);

namespace Booking\App\Controller;

use Booking\Booking\ActiveBooking\Domain\ActiveBookingNotFound;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        /** @var HandlerFailedException $exception */
        $exception = $event->getThrowable()->getPrevious();

        $status = $this->status($exception);
        $response = new JsonResponse([
            'error' => $exception->getMessage(),
            'reason' => $this->reason($exception),
        ], $status);

        $event->setResponse($response);
    }

    private function status(Throwable $e): int
    {
        if (
            $e instanceof InvalidValueException
        ) {
            return Response::HTTP_BAD_REQUEST;
        }

        if ($e instanceof ActiveBookingNotFound) {
            return Response::HTTP_NOT_FOUND;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    private function reason(Throwable $e): string
    {
        if (
            $e instanceof InvalidValueException
        ) {
            return 'http.bad.request';
        }

        if ($e instanceof ActiveBookingNotFound) {
            return 'resource.not.found';
        }

        return 'internal.server.error';
    }
}
