<?php

use Lockmaey\LaravelCommon\Enums\ErrorCode;
use Lockmaey\LaravelCommon\Exceptions\ApiException;
use Lockmaey\LaravelCommon\Interfaces\ErrorCodeInterface;

/**
 * @throws ApiException
 */
function abortWithError(ErrorCodeInterface $errorCode = ErrorCode::UNKNOWN): void
{
    throw new ApiException($errorCode);
}
