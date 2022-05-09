<?php

declare(strict_types=1);

namespace Customerce\JsonApiProblemResponder\EventListener;

use Customerce\JsonApiProblemResponder\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $data = $this->getExceptionDefaults($exception);

        if ($exception instanceof BaseException) {
            $data = $exception->toArray();
        }

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
}