<?php

declare(strict_types=1);

namespace Booking\Apps\Controller\HealthChecker;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HealthCheckerController
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse(
            [
                'booking' => 'ok',
            ]
        );
    }
}
