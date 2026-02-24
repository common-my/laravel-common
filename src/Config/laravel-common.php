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
];
