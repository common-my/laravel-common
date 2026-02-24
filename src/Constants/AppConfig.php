<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Constants;

class AppConfig
{
    public static string $appName;
    public static int $paginationLimit;
    public static int $appRequestLimit;
    public static string $s3Bucket;

    public static int $authPasswordExpiry;

    public static int $authAccessTokenExpiry;
    public static int $authRefreshTokenExpiry;

    public static function init(): void
    {
        //common
        self::$appName = (string)config('app.name');
        self::$paginationLimit = (int)config('app.pagination.limit');
        self::$appRequestLimit = (int)config('app.request.limit');
        self::$s3Bucket = (string)config('filesystems.disks.s3.bucket');
        self::$authPasswordExpiry = (int)config('auth.passwords.users.expire');
        self::$authAccessTokenExpiry = (int)config('sanctum.ac_expiration');
        self::$authRefreshTokenExpiry = (int)config('sanctum.rt_expiration');
    }
}
