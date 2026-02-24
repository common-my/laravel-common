<?php

use CommonMy\LaravelCommon\Enums\ErrorCode;
use CommonMy\LaravelCommon\Exceptions\ApiException;
use CommonMy\LaravelCommon\Interfaces\ErrorCodeInterface;

/**
 * @throws ApiException
 */
function abortWithError(ErrorCodeInterface $errorCode = ErrorCode::UNKNOWN): void
{
    throw new ApiException($errorCode);
}
