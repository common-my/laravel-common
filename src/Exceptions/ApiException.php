<?php

declare(strict_types=1);

namespace Lockmaey\LaravelCommon\Exceptions;

use Exception;
use Throwable;
use Lockmaey\LaravelCommon\Interfaces\ErrorCodeInterface;

class ApiException extends Exception
{
    protected ErrorCodeInterface $errorCode;

    public function __construct(ErrorCodeInterface $errorCode, string $message = '', ?Throwable $previous = null)
    {
        $this->errorCode = $errorCode;

        $message = $message ?: $errorCode->message();

        parent::__construct($message, (int)$errorCode->value(), $previous);
    }

    public function getErrorCode(): ErrorCodeInterface
    {
        return $this->errorCode;
    }

    public function getHttpStatusCode(): int
    {
        return $this->errorCode->httpCode();
    }

    public function getTitle(): string
    {
        return $this->errorCode->title();
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'error_code' => $this->errorCode->value(),
            'http_status_code' => $this->getHttpStatusCode(),
        ];
    }
}
