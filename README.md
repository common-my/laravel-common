# Laravel Common Package

A foundational common package for Laravel applications by common-my. This package provides a solid base of shared utilities, traits, interfaces, enums, and helpers meant to be reused across different projects.

Repository: [common-my/laravel-common](https://github.com/common-my/laravel-common)

## Installation

You can install the package via composer:

```bash
composer require common-my/laravel-common
```

Then publish the configuration file:
```bash
php artisan vendor:publish --tag=laravel-common-config
```

## Features

This package provides numerous foundational tools to enforce strict typing, clean error handling, and reusable logic across your Laravel stack.

### Middlewares

- **ResponseFormat**: Automatically formats JSON success responses to include `success: true`, `data`, `__message`, and standardized `meta` for pagination.
- **InitTenancy**: Standardized tenant resolution via `X-Tenant` or `App-Id` headers. Supports auto-initialization via `stancl/tenancy`.
- **ForceJsonResponse**: Simple middleware to set the `Accept: application/json` header for all requests.

### Interfaces & Enums

- **ErrorCodeInterface**: Defines a strict contract for application error codes (`title()`, `message()`, `httpCode()`, `label()`, `value()`).
- **ErrorCode Enum**: A standard set of foundational error integer cases (e.g., `UNKNOWN`, `VALIDATION_ERROR`, etc.) implementing the `ErrorCodeInterface`.

### Exceptions & Error Handling

- **ApiException**: An exception class that strictly accepts `ErrorCodeInterface`. It provides structured data for API error responses.
- **Abort Helper**: A globally accessible `abortWithError($errorCode)` function that throws `ApiException` smoothly.

### Traits

- **HasEnumArray**: Extracts an enum into a standard key-value associative array matrix.
- **HasEnumValue**: Simplifies extracting raw data from enumeration instances.

## Usage

### Middleware Setup (Laravel 11+)

In your `bootstrap/app.php`:

```php
use CommonMy\LaravelCommon\Http\Middleware\ResponseFormat;
use CommonMy\LaravelCommon\Http\Middleware\InitTenancy;
use CommonMy\LaravelCommon\Http\Middleware\ForceJsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(InitTenancy::class);
        $middleware->append(ForceJsonResponse::class);
        $middleware->append(ResponseFormat::class);
    })
    // ...
```

### Configuration

The `config/laravel-common.php` allows you to customize tenant resolution:

```php
return [
    'tenant_model' => 'App\Models\Tenant',
    'initialize_tenancy' => true, // Auto-call tenancy()->initialize()
];
```

### Exception Formatting

Use the standard custom exceptions seamlessly across your application controllers or services:

```php
use CommonMy\LaravelCommon\Enums\ErrorCode;

abortWithError(ErrorCode::TENANT_NOT_FOUND);
```

### Implementing Custom Error Enums 

If your main application has custom error states, create your own enum implementing the `ErrorCodeInterface`:

```php
namespace App\Enums;

use CommonMy\LaravelCommon\Interfaces\ErrorCodeInterface;

enum AppErrorCode: int implements ErrorCodeInterface {
    case INVENTORY_MISSING = 5001;

    public function title(): string { return 'Inventory Error'; }
    public function message(): string { return 'The requested item is out of stock.'; }
    public function httpCode(): int { return 400; }
    public function label(): string { return 'Inventory Missing'; }
    public function value(): int { return $this->value; }
}

// Still works with the base exception!
abortWithError(AppErrorCode::INVENTORY_MISSING);
```

## License

The MIT License (MIT). Please see License File for more information.
