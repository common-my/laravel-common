<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitTenancy
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip CORS preflight, documentation or asset routes
        if (
            $request->isMethod('OPTIONS') ||
            $request->is('*api-docs*') ||
            $request->is('*documentation*')
        ) {
            return $next($request);
        }

        $tenantId = $request->header('X-Tenant');
        $appId = $request->header('App-Id') ?? $request->header('app-id');

        $tenantModel = config('laravel-common.tenant_model', 'App\Models\Tenant');
        $tenant = null;

        if ($tenantId) {
            $tenant = $tenantModel::query()->find($tenantId);
        } elseif ($appId) {
            $tenant = $tenantModel::query()->where('app_id', $appId)->first();
            if ($tenant) {
                // Standardize header for subsequent middlewares (like Stancl\Tenancy)
                $request->headers->set('X-Tenant', $tenant->id);
            }
        }

        // Initialize tenancy if found and configured
        if ($tenant && function_exists('tenancy') && config('laravel-common.initialize_tenancy', true)) {
            tenancy()->initialize($tenant);
        }

        // Ensure locale header exists
        if (!$request->headers->has('X-Locale')) {
            $request->headers->set(
                'X-Locale',
                config('app.locale', 'en')
            );
        }

        return $next($request);
    }
}
