<?php

declare(strict_types=1);

namespace Customerce\JsonApiProblemResponder\EventListener;

use Customerce\JsonApiProblemResponder\Exceptions\BaseException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $data = $this->getExceptionDefaults($exception);

        if ($exception instanceof BaseException) {
            $data = $exception->toArray();
        }

        $this->logException($exception);

        $response = new JsonResponse($data, $data['status'], ['Content-Type' => 'application/problem+json']);

        $event->setResponse($response);
    }

    protected function getExceptionDefaults(Throwable $exception): array
    {
        return [
            'status' => 500,
            'title' => 'Uncaught Error',
            'detail' => $exception->getMessage(),
            'type' => 'https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
        ];
    }

    protected function logException(Throwable $exception): void
    {
        $message = sprintf(
            'An error occured: %s with code: %s \n %s',
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getTraceAsString(),
        );

        $this->logger->error($message);
    }
}