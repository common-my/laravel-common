<?php

namespace CommonMy\LaravelCommon\Traits;

trait HasEnumValue
{
    public function value(): string|int
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
