<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Http\Middleware;

use Closure;
use CommonMy\LaravelCommon\Enums\ErrorCode;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByBrand
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('OPTIONS') ||
            $request->is('*api-docs*') ||
            $request->is('*documentation*')
        ) {
            return $next($request);
        }

        $brandId = $request->header('X-Brand');

        if (!$brandId) {
            abortWithError(ErrorCode::HEADER_MISSING);
        }

        /** @var class-string<\Illuminate\Database\Eloquent\Model> $tenantModel */
        $tenantModel = config('laravel-common.tenant_model', 'App\Models\Tenant');

        $tenant = $tenantModel::query()
            ->find($brandId);

        if (!$tenant) {
            abortWithError(ErrorCode::TENANT_NOT_FOUND);
        }

        if (function_exists('tenancy') && config('laravel-common.initialize_tenancy', true)) {
            tenancy()->initialize($tenant);
        }

        if (!$request->headers->has('X-Locale')) {
            $request->headers->set(
                'X-Locale',
                (string) config('app.locale', 'en')
            );
        }

        return $next($request);
    }
}
