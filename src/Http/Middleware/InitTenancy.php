<?php

declare(strict_types=1);

namespace CommonMy\LaravelCommon\Http\Middleware;

use Closure;
use CommonMy\LaravelCommon\Enums\ErrorCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;
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

        $brandId = $request->header('X-Brand');
        $tenantId = $request->header('X-Tenant');
        $appId = $request->header('App-Id') ?? $request->header('app-id');

        /** @var class-string<Model> $tenantModel */
        $tenantModel = config('laravel-common.tenant_model', 'App\Models\Tenant');
        $parentTenant = null;

        // Use central connection if available to avoid using tenant connection for lookup
        $centralConnection = (string) config('tenancy.database.central_connection', 'mysql');

        // Resolve parent first
        if ($tenantId) {
            $parentTenant = $tenantModel::on($centralConnection)->find($tenantId);
            if (!$parentTenant && class_exists(TenantCouldNotBeIdentifiedById::class)) {
                throw new TenantCouldNotBeIdentifiedById((string) $tenantId);
            }
        } elseif ($appId) {
            $parentTenant = $tenantModel::on($centralConnection)->where('app_id', $appId)->first();
        }

        if ($brandId) {
            // Requirement: brand must exists with (app-id or xtenant)
            if (!$parentTenant) {
                abortWithError(ErrorCode::HEADER_MISSING);
            }

            // Check if brand belongs to organization
            $isMember = DB::connection($centralConnection)
                ->table((string) config('laravel-common.organization_tenant_table', 'organization_tenant'))
                ->where('organization_id', $parentTenant->id)
                ->where('tenant_id', $brandId)
                ->exists();

            if (!$isMember) {
                abortWithError(ErrorCode::TENANT_NOT_FOUND);
            }

            $tenant = $tenantModel::on($centralConnection)->find($brandId);
            if (!$tenant) {
                abortWithError(ErrorCode::TENANT_NOT_FOUND);
            }
        } else {
            $tenant = $parentTenant;
        }

        // Initialize tenancy if found and configured
        if ($tenant && function_exists('tenancy') && config('laravel-common.initialize_tenancy', true)) {
            tenancy()->initialize($tenant);
        }

        // Set standardized header if resolved
        if ($tenant) {
            $request->headers->set('X-Tenant', (string) $tenant->id);
        }

        // Ensure locale header exists
        if (!$request->headers->has('X-Locale')) {
            $request->headers->set(
                'X-Locale',
                (string) config('app.locale', 'en')
            );
        }

        return $next($request);
    }
}
