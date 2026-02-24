<?php

declare(strict_types=1);

namespace Lockmaey\LaravelCommon\Enums;

use Symfony\Component\HttpFoundation\Response;
use Lockmaey\LaravelCommon\Interfaces\ErrorCodeInterface;
use Lockmaey\LaravelCommon\Traits\HasEnumArray;
use Lockmaey\LaravelCommon\Traits\HasEnumValue;

enum ErrorCode: int implements ErrorCodeInterface
{
    use HasEnumArray;
    use HasEnumValue;

    case UNKNOWN = 0000;
    case HEADER_MISSING = 10;
    case HEADER_INVALID = 11;
    case HEADER_INVALID_VALUE = 12;
    case HEADER_DECRYPTION_FAILED = 13;

    case VALIDATION_ERROR = 2001;

    case CAPTCHA_ERROR = 9001;

    case ITEM_NOT_SAVED = 10000;

    case fileNotfound = 2000;

    case TENANT_NOT_FOUND = 401;

    public function title(): string
    {
        return match ($this) {
            self::UNKNOWN => __('Internal Server Error'),
            self::HEADER_MISSING,
            self::HEADER_INVALID,
            self::HEADER_INVALID_VALUE,
            self::HEADER_DECRYPTION_FAILED => __('Unrecognized Request'),
            self::CAPTCHA_ERROR => __('Captcha Error'),
            self::VALIDATION_ERROR => __('Validation Error'),
            self::ITEM_NOT_SAVED => __('Item Not Saved'),
            self::fileNotfound => __('File Not Found'),
            self::TENANT_NOT_FOUND => __('Unauthorized'),
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::UNKNOWN => __('Internal Server Error'),
            self::HEADER_MISSING,
            self::HEADER_INVALID,
            self::HEADER_INVALID_VALUE,
            self::HEADER_DECRYPTION_FAILED => __(
                'An unexpected error occurred while processing your request. Please try again later.'
            ),
            self::CAPTCHA_ERROR => __('Captcha Error'),
            self::VALIDATION_ERROR => __('The given data was invalid.'),
            self::ITEM_NOT_SAVED => __('Item Not Saved'),
            self::fileNotfound => __('File Not Found'),
            self::TENANT_NOT_FOUND => __('Unauthorized access. Please contact support.'),
        };
    }

    public function httpCode(): int
    {
        return match ($this) {
            self::UNKNOWN => Response::HTTP_NOT_IMPLEMENTED,
            self::HEADER_MISSING,
            self::HEADER_INVALID,
            self::HEADER_INVALID_VALUE,
            self::HEADER_DECRYPTION_FAILED,
            self::fileNotfound => Response::HTTP_BAD_REQUEST,
            self::CAPTCHA_ERROR => Response::HTTP_NOT_ACCEPTABLE,
            self::VALIDATION_ERROR => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::ITEM_NOT_SAVED => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::TENANT_NOT_FOUND => Response::HTTP_UNAUTHORIZED,
        };
    }

    public function label(): string
    {
        return $this->title();
    }
}
