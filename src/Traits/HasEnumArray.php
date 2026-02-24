<?php

namespace Lockmaey\LaravelCommon\Traits;

trait HasEnumArray
{
    public function toArray(): array
    {
        return [
            'label' => $this->label(),
            'value' => $this->value(),
        ];
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[] = $case->toArray();
        }

        return $options;
    }
}
