<?php

return [
    'name' => 'LaravelCommon',
    
    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    |
    | The model class to use for tenant resolution.
    |
    */
    'tenant_model' => 'App\Models\Tenant',

    /*
    |--------------------------------------------------------------------------
    | Initialize Tenancy
    |--------------------------------------------------------------------------
    |
    | Whether to automatically initialize tenancy using stancl/tenancy.
    |
    */
    'initialize_tenancy' => true,

    /*
    |--------------------------------------------------------------------------
    | Organization Tenant Pivot Table
    |--------------------------------------------------------------------------
    |
    | The table name used for organization-brand relationship verification.
    |
    */
    'organization_tenant_table' => 'organization_tenant',
];
