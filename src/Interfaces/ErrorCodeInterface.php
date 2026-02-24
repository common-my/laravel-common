<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Interfaces;

interface ErrorCodeInterface
{
    public function title(): string;
    public function message(): string;
    public function httpCode(): int;
    public function label(): string;
    public function value(): int|string;
}
