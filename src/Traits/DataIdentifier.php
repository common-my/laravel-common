<?php

namespace CommonMy\LaravelCommon\Traits;

use App\Models\CentralUser;

trait DataIdentifier
{
    public static function booted(): void
    {
        self::creating(function ($model) {
            $model->created_by = $model->created_by
                ?? auth('sanctum')->user()->uuid
                ?? auth()->id()
                ?? CentralUser::system()?->uuid;
        });

        self::created(function ($model) {});

        self::updating(function ($model) {
            if (auth('sanctum')->user()) {
                $model->updated_by = auth('sanctum')->user()->uuid ?? auth()->id();
            }
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            if (auth('sanctum')->user()) {
                $model->updated_by = auth('sanctum')->user()->uuid;
            }
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }
}
