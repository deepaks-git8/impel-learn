<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class KernelExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        [$statusCode, $statusText ] = match (true) {
            $exception instanceof HttpExceptionInterface
            => (function () use($exception): array {
                return [
                    $exception->getStatusCode(),
                    $exception->getMessage() ?: $exception->getStatusCode()
                ];
            })(),

            default => [500, 'Internal Server Error']
        };

        // Create a JSON response
        $response = new JsonResponse([
            'status' => $statusCode,
            'error' => $statusText,
            'message' => $exception->getMessage(),
        ], $statusCode);

        // Set the response on the event
        $event->setResponse($response);

        // Log the exception
        $this->logger->error('[EXCEPTION] ' . $exception->getMessage(), [
            'exception' => $exception,
        ]);
    }
}
