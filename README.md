# Laravel Common Package

A foundational common package for Laravel applications by lockmaey. This package provides a solid base of shared utilities, traits, interfaces, enums, and helpers meant to be reused across different projects.

## Installation

You can install the package via composer:

```bash
composer require lockmaey/laravel-common
```

## Features

This package provides numerous foundational tools to enforce strict typing, clean error handling, and reusable logic across your Laravel stack.

### Interfaces & Enums

- **ErrorCodeInterface**: Defines a strict contract for application error codes (`title()`, `message()`, `httpCode()`, `label()`, `value()`).
- **ErrorCode Enum**: A standard set of foundational error integer cases (e.g., `UNKNOWN`, `VALIDATION_ERROR`, etc.) implementing the `ErrorCodeInterface`.

### Exceptions & Error Handling

- **ApiException**: An exception class that strictly accepts `ErrorCodeInterface`.
- **Abort Helper**: A globally accessible `abortWithError($errorCode)` function that throws `ApiException` smoothly.

### Traits

- **HasEnumArray**: Extracts an enum into a standard key-value associative array matrix.
- **HasEnumValue**: Simplifies extracting raw data from enumeration instances.
- **DataIdentifier**: Useful shared scopes or identifiers for models or robust data structures.

### Helpers

- **ArrayHelper**: Common array manipulation logic.
- **ImageHelper**: Common image manipulation logic.
- **NumberHelper**: Common number formatting logic.
- **Response**: Centralized, unified JSON response builder schemas.

## Usage

### Exception Formatting
Use the standard custom exceptions seamlessly across your application controllers or services:

```php
use Lockmaey\LaravelCommon\Enums\ErrorCode;

abortWithError(ErrorCode::TENANT_NOT_FOUND);
```

### Implementing Custom Error Enums 
If your main application has custom error states, create your own enum implementing the `ErrorCodeInterface`:

```php
namespace App\Enums;

use Lockmaey\LaravelCommon\Interfaces\ErrorCodeInterface;

enum AppErrorCode: int implements ErrorCodeInterface {
    case INVENTORY_MISSING = 5001;

    public function title(): string { ... }
    public function message(): string { ... }
    public function httpCode(): int { ... }
    public function label(): string { ... }
    public function value(): int { ... }
}

// Still works with the base exception!
abortWithError(AppErrorCode::INVENTORY_MISSING);
```

## License

The MIT License (MIT). Please see License File for more information.
